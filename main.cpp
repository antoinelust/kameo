#include <Arduino.h>
#include <Keypad.h>
#include <DogLcd.h>
#include "PISO_74HC165.h"
#include "SIPO_74HC595.h"
#include "Rfid.h"
#include <avr/wdt.h> // Reset Arduino en cas de probleme
#include <ArduinoHttpClient.h>
#include <Ethernet.h>

// Constantes a changer selon l'armoire
#define TIMEOUT 15000		  // TEMPS D'ATTENTE PORTE ET CLE
#define BUILDING "venturelab" // requete sql			  
#define NOM_VELO "VEN" // 3 caractères qui désigne le vélo dans la DB et qui sont liées à l'entreprise
					   // c'est caractère se trouvent actuellement dans le secteur 1 block 0  
#define NB_MODULE 1
#define FONCTIONNEMENT 0 // Definit le type de fonctionnement:
						 // soit par CODE: 0 
						 // soit par CARD: 1
byte mac_addr[] = {0xA8, 0x61, 0x0A, 0xAE, 0x13, 0x0C}; // sur le shield ethernet

// Constantes
#define RFID_CTS_PIN 22		  // RFID
#define RFID_INTERRUPT 23	 // RFID
#define KEY_ROWS 4			  // Keypad
#define KEY_COLS 3			  // Keypad
#define MOSI_PIN 33			  // LCD
#define SCK_PIN 34			  // LCD
#define CSB_PIN 36			  // LCD
#define RS_PIN 35			  // LCD
#define LCD_LINES 3			  // LCD
#define SWITCH_PLOAD_PIN 6	// PISO switch
#define SWITCH_CLK_EN_PIN 5   // PISO switch
#define SWITCH_DATA_PIN 3	 // PISO switch
#define SWITCH_CLK_PIN 2	  // PISO switch
#define LED_LATCH_PIN 7		  // SIPO Led
#define LED_CLK_PIN 9		  // SIPO Led
#define LED_DATA_PIN 8		  // SIPO Led
#define SOLE_ARM_PIN 31		  // Ouverture et fermeture armoire
#define CAPT_ARM_PIN 32		  // Ouverture et fermeture armoire
#define BUZZ_PIN 11			  // Buzzer
#define RELAY_PIN 17		  // Activer le 24V
#define MAX_EMPL 5*NB_MODULE			// requete sql

// Global
enum mode // 3 modes differents pour effectuer les actions propres a chaque mode
{
	WAIT,
	SETTINGS,
	IN,
	OUT,
	OUT_CARD
};
mode actual_mode = WAIT;
enum step
{
	Sortir,
	Sol_ext,
	Capt_ext,
	RFID_UID,
	Mettre_cle,
	Test_sol,
	Test_LED,
	RFID_read,
	RFID_write,
	Enlever_cle,
	LCD_test,
	Code
};
step selected_step = Sortir;
step used_step = Sortir;
unsigned long milli;
int timer_buzz_porte = 0;

// Fonctions

//Fonction pour recevoir le code dans setting et fonction normale
bool entrer_code(char code[5]);
bool entrer_code(char code[5], char first_number);
int verifier_code(HttpClient clt, char code[5], const char building[]); // retourne emplacement si CODE ok
//-----------------------------------------------------------------------------------------
//Fonction pour actionner les solénoides et communiquer avec BD
bool enlever_cle(int empl);
bool envoyer_cle_prise_code(HttpClient clt, char code[5], int empl, const char building[]);
bool envoyer_cle_prise_card(HttpClient clt, int empl, char UID[9], const char building[]); 
// bool envoyer_cle_prise(HttpClient clt, String frame_number, int empl, const char building[], String email);
//-----------------------------------------------------------------------------------------
// Fonction permettant la Gestion entre les deux type de RFID et la fonction resérvation avec CARTE
String verifier_rfid_card(char rfid[16], const char nom_velo[]); // Detecte si il s'agit d'un vélo ou d'un client
int verifier_rfid_client(HttpClient clt,char uid[9], const char building[]); // compare UID reçu avec celui de la BD
bool verifier_rfid_velo(HttpClient clt, char nom[16]); //Sécurité pour RFID vélo
// String verifier_rfid(HttpClient clt, char uid[9], const char building[]);

int mettre_cle(HttpClient clt, int max_empl, const char building[]);
int emplacement_libre(HttpClient clt, int max_empl, const char building[]);
bool envoyer_cle_remise(HttpClient clt, char nom[16], int empl, const char building[]);
bool out_sans_reservation(String rep, HttpClient clt, const char building[]);
void alarme_porte(int temps, bool affiche_menu, int *tmr);
void test_Ethernet(const char srv[], unsigned long *tmr, int *compteur_erreur);
void menu_lcd_changer_etat(step selected);
void menu_lcd_action(step used);

// Ethernet
EthernetClient client;
const char server[] = "www.kameobikes.com";
//const char server[] = "192.168.137.1";
HttpClient http_clt = HttpClient(client, server, 80); // port 80 http
int test_ethernet_error = 0;
unsigned long timer_ethernet_error = 0;

// Keypad
char hexaKeys[KEY_ROWS][KEY_COLS] = {
	{'1', '2', '3'},
	{'4', '5', '6'},
	{'7', '8', '9'},
	{'*', '0', '#'}};
byte rowPins[KEY_ROWS] = {27, 28, 29, 30};
byte colPins[KEY_COLS] = {24, 25, 26};
Keypad myKeypad = Keypad(makeKeymap(hexaKeys), rowPins, colPins, KEY_ROWS, KEY_COLS);
char keypad_code[5];

// LCD
DogLcd lcd(MOSI_PIN, SCK_PIN, RS_PIN, CSB_PIN);

// RFID
Rfid rfid_lecteur(RFID_CTS_PIN);

// I/O
bool input[8*NB_MODULE]; // SWITCH // 0 = A du dernier, 7 = H du dernier, 8 = A du premier, 15 = H du premier
/*
  Sw1 = input[0]
  Sw2 = input[1]
  ---
  Sw10 = input[9]
*/
PISO_74HC165 switch_74hc165(SWITCH_PLOAD_PIN, SWITCH_CLK_EN_PIN, SWITCH_DATA_PIN, SWITCH_CLK_PIN); // Lecture des entrees, initialisation classe
bool output[2*8*NB_MODULE] = {0}; // LED + ELECTRO // 0 = A du dernier, 7 = H du dernier, ...
/*
  L1 = output[0]
  ---
  L10 = output[9]
  Sol1 = output[10]
  ---
  Sol10 = output[19]
*/
SIPO_74HC595 out_74hc595(LED_LATCH_PIN, LED_DATA_PIN, LED_CLK_PIN); // Ecriture des sorties

/*--------------------------------------------------*/
void setup()
{
	Serial.begin(9600);	// Utilise pour les tests, moniteur serie

	rfid_lecteur.init();
	switch_74hc165.init();
	out_74hc595.init(output);

	pinMode(RELAY_PIN, OUTPUT);
	pinMode(CSB_PIN, OUTPUT);
	pinMode(SOLE_ARM_PIN, OUTPUT);
	pinMode(4, OUTPUT);		//  Pour ne pas avoir de problème avec le shield Ethernet. Conflit avec le lecteur de carte sd
	digitalWrite(4, HIGH);  //
	pinMode(10, OUTPUT);	//
	digitalWrite(10, HIGH); //

	digitalWrite(CSB_PIN, HIGH);
	lcd.begin(DOG_LCD_M163, 35, DOG_LCD_VCC_5V);
	lcd.noCursor();
	lcd.print_total("----------------", "----Connexion---", "----------------");

	// Keycode clé
	rfid_lecteur.change_key_code(0x04, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF);

	Serial.println("Setup ok");

	Serial.println("Initialize Ethernet with DHCP:");
	if (Ethernet.begin(mac_addr) == 0)
	{
		Serial.println("Failed to configure Ethernet using DHCP");
		lcd.print_total("Erreur Ethernet");
		// Check for Ethernet hardware present
		if (Ethernet.hardwareStatus() == EthernetNoHardware)
		{
			Serial.println("Ethernet shield was not found.  Sorry, can't run without hardware. :(");
		}
		if (Ethernet.linkStatus() == LinkOFF)
		{
			Serial.println("Ethernet cable is not connected.");
		}
		lcd.print_total("----------------", "--KAMEO  Bikes--", "Erreur connexion");
	}
	else
	{
		Serial.print("  DHCP assigned IP ");
		Serial.println(Ethernet.localIP());
		lcd.print_menu();
		delay(500);
	}
	lcd.print_total("----------------", "--KAMEO  Bikes--", "----------------");

	digitalWrite(RELAY_PIN, HIGH); // Activer le 24V une fois que tout est lance. Pour ne pas que les switchs s'activent sans raison au demarrage
}

/*--------------------------------------------------*/
void loop()
{
	switch_74hc165.get_value(input); // MAJ entrees
	out_74hc595.set_value(output);   // MAJ sorties
	//switch_74hc165.print_value();  // Debug pour afficher ce qui est envoyé aux registres

	char key_pressed = myKeypad.getKey(); // Verification touche pressee
	if (key_pressed)
	{
		int number = key_pressed - '0'; // Pour obtenir un nombre a partir du caractere ASCII
		if (key_pressed == '*')			// SETTINGS
		{
			//On entre dans un menu deroulant ou il est possible d'effectuer toutes les actions seules.
			//Utilise pour tester chaque fonctionnalite

			Serial.println("SETTINGS");
			actual_mode = SETTINGS;
			menu_lcd_changer_etat(selected_step);
			while (actual_mode == SETTINGS) // Tant qu'on est dans le mode SETTINGS, On regarde si on appuie sur une des trois touche d'action
			{
				char key_pressed_settings = myKeypad.getKey();
				if (key_pressed_settings)
				{
					switch (key_pressed_settings)
					{
					case '3': // Monter
					{
						selected_step = step(selected_step - 1); // On change l'etape
						menu_lcd_changer_etat(selected_step);	// On actualise l'ecran
						break;
					}
					case '9': // Descendre
					{
						selected_step = step(selected_step + 1); // On change l'etape
						menu_lcd_changer_etat(selected_step);	// On actualise l'ecran
						break;
					}
					case '#': // Valider
					{
						used_step = selected_step;  // On actualise l'etape courante
						menu_lcd_action(used_step); // On entre dans la routine de l'etape voulue
						break;
					}
					}
				}
			}
		}
		else if (number >= 0 && number < 10 && FONCTIONNEMENT == 0 ) // OUT // Debut d'un code, si le fonctionnement code (0) est activé
		{
			Serial.println("OUT");
			actual_mode = OUT;
			int emplacement;
			// Entrée du code
			if (!entrer_code(keypad_code, key_pressed))
			{
				Serial.println("Timeout");
				goto end_out;
			}
			// Vérification du code
			lcd.print_total("Verification", "code");
			emplacement = verifier_code(http_clt, keypad_code, BUILDING);
			if (emplacement == -1)
			{
				Serial.println("Code non ok ou vélo pas disponible");
				goto end_out;
			}

			// Ouverture de l'armoire
			digitalWrite(SOLE_ARM_PIN, HIGH); // Actionner solenoides de la porte
			lcd.print_total("Ouvrir la porte");
			milli = millis();
			while (!digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis()) // On attend que soit la porte soit ouverte ou que le timeout soit depasse
			{
			}
			if ((milli + TIMEOUT) <= millis() && !digitalRead(CAPT_ARM_PIN)) // Le temps a depasse le timeout et la porte est toujours fermee, on bloque donc la porte
			{
				Serial.println("Temps ecoule");
				digitalWrite(SOLE_ARM_PIN, LOW);
				goto end_out;
			}
			Serial.println("La porte est bien ouverte");
			// Deverrouillage bonne cle
			lcd.print_total("Prendre cle", "Emplacement : ");
			lcd.print(emplacement);
			if (!enlever_cle(emplacement))
			{
				Serial.println("Erreur dans la prise de clé");
				goto end_out;
			}
			Serial.println("Prise de clé reussie");
			// Fermeture de l'armoire
			lcd.print_total("Fermer la porte");
			milli = millis();
			while (digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis()) // On attend que soit la porte soit ouverte ou que le timeout soit depasse
			{
			}
			if ((milli + TIMEOUT) <= millis() && digitalRead(CAPT_ARM_PIN)) // Le temps a depasse le timeout et la porte est toujours ouverte, on fait sonner l'alarme jusqu'a la fermeture
			{
				Serial.println("Temps écoulé");
				tone(BUZZ_PIN, 500);
				lcd.print_total("Fermer la porte", "Temps ecoule");
				while (digitalRead(CAPT_ARM_PIN)) // Tout est bloque en attendant que la porte soit fermee
				{
				}
			}
			noTone(BUZZ_PIN);
			digitalWrite(SOLE_ARM_PIN, LOW);
			Serial.println("La porte est bien fermée");
			// Envoyer message pour dire que la cle est bien prise et adapter la base de donnees
			envoyer_cle_prise_code(http_clt, keypad_code, emplacement, BUILDING);
		end_out:
			actual_mode = WAIT; // Fin de la prise de cle
			lcd.print_menu();
		}
	else if (number >= 0 && number < 10 && FONCTIONNEMENT == 1) // si le fonctionnement CARD (1) activé
		{
			Serial.println("Fonction code indisponnible");
			lcd.print_total("Fonction code indisponnible");
			delay(5000);
			actual_mode = WAIT;
			lcd.print_menu();
		}
	}
	else if (!digitalRead(RFID_INTERRUPT)) // IN ou OUT_CARD // Verification RFID, quand la pin est a 0, cela veut dire qu'un tag est presente devant l'antenne
	{
       	//Verification du tag RFID
		 rfid_lecteur.print_UID();
		 rfid_lecteur.read(0x04, 0x04); //read(byte key_number, byte block_number) 0x04, 0x04 signifie le secteur 1 bloc 0 , le key_number est le mot de passe pour lire dans la zone
		 								// 0x05, 0x05 pour le secteur 1 bloc 1
										// 0x38, 0x38 pour le secteur 14 bloc 0
		 Serial.println(rfid_lecteur.rfid_read_char);
	
		String reponse_rfid = verifier_rfid_card(rfid_lecteur.rfid_read_char, NOM_VELO);  
		lcd.print_total("Verification", "RFID");
		delay(2000);
		lcd.print_total(rfid_lecteur.UID_char, rfid_lecteur.rfid_read_char); // Affiche UID et les identifiants Vélo
		delay(2000);
		if (reponse_rfid == "0")
		{
			Serial.println("RFID non ok");
			lcd.print_total("RFID", "VIDE");
			delay(5000);
			goto end_in;

		}

	    else if(reponse_rfid == "CLIENT_CARD" && FONCTIONNEMENT == 1) //OUT // c'est un RFID client et le mode CARD (1) activé 
		{
			Serial.println("OUT_CARD");
			actual_mode = OUT_CARD;
			int emplacement;
			rfid_lecteur.print_UID();
		

			// Verification du nom du client
			lcd.print_total("Verification", "RFID client");
			delay(2000);

			emplacement = verifier_rfid_client(http_clt, rfid_lecteur.UID_char, BUILDING);// A FAIRE client doit verif que c'est bien un client et qu'il a reservé, emplacement est la réponse
			if(emplacement == -1) 
			{ 
			Serial.println("RFID non ok");
			lcd.print_total("Problème de ","reservation", "ou delai");
	     	delay(5000);
			goto end_out_card;
			}
	
			// Ouverture de l'armoire
			digitalWrite(SOLE_ARM_PIN, HIGH); // Actionner solenoides de la porte
			lcd.print_total("Ouvrir la porte");
			milli = millis();
			while (!digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis()) // On attend que soit la porte soit ouverte ou que le timeout soit depasse
			{
			}
			if ((milli + TIMEOUT) <= millis() && !digitalRead(CAPT_ARM_PIN)) // Le temps a depasse le timeout et la porte est toujours fermee, on bloque donc la porte
			{
				Serial.println("Temps ecoule");
				digitalWrite(SOLE_ARM_PIN, LOW);
				goto end_out_card;
			}
			Serial.println("La porte est bien ouverte");
			// Deverrouillage bonne cle
			lcd.print_total("Prendre cle", "Emplacement : ");
			lcd.print(emplacement);
			if (!enlever_cle(emplacement))
			{
				Serial.println("Erreur dans la prise de clé");
				goto end_out_card;
			}
			Serial.println("Prise de clé reussie");
			// Fermeture de l'armoire
			lcd.print_total("Fermer la porte");
			milli = millis();
			while (digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis()) // On attend que soit la porte soit ouverte ou que le timeout soit depasse
			{
			}
			if ((milli + TIMEOUT) <= millis() && digitalRead(CAPT_ARM_PIN)) // Le temps a depasse le timeout et la porte est toujours ouverte, on fait sonner l'alarme jusqu'a la fermeture
			{
				Serial.println("Temps écoulé");
				tone(BUZZ_PIN, 500);
				lcd.print_total("Fermer la porte", "Temps ecoule");
				while (digitalRead(CAPT_ARM_PIN)) // Tout est bloque en attendant que la porte soit fermee
				{
				}
			}
			noTone(BUZZ_PIN);
			digitalWrite(SOLE_ARM_PIN, LOW);
			Serial.println("La porte est bien fermée");
			// Envoyer message pour dire que la cle est bien prise et adapter la base de donnees
			envoyer_cle_prise_card(http_clt, emplacement, rfid_lecteur.UID_char, BUILDING);

			end_out_card:
			actual_mode = WAIT; // Fin de la prise de cle
			lcd.print_menu();
		}
		else if ( reponse_rfid == "CLIENT_CARD" && FONCTIONNEMENT == 0) // si le fonctionnement CODE (0) activé et que RFID pas vélo
		{
			Serial.println("Fonction carte client indisponnible");
			lcd.print_total("Fonction carte","client","indisponnible");
			delay(5000);
			actual_mode = WAIT;
			lcd.print_menu();
		}
		else if(reponse_rfid == "VELO") // c'est le RFID velo
		{
			Serial.println("IN");
			actual_mode = IN;
			int emplacement;

			// Obtention du nom du vélo
			// rfid_lecteur.read(0x04, 0x04);
			// Serial.println(rfid_lecteur.rfid_read_char);
			// Verification du nom du velo
			lcd.print_total("Verification", "RFID velo");
			delay(2000);

			if (!verifier_rfid_velo(http_clt, rfid_lecteur.rfid_read_char)) // appelle fonction pour verifier si le velo est bien parti. en donnant le nom du velo en deuxieme parametre
			{
				Serial.println("RFID non ok");
				lcd.print_total("Probleme velo", "RFID non ok");
	    		delay(5000);
				goto end_in;
			
			}

			/////////////////////////////////////
			////////////////////////////////////
			// Verification du tag RFID
			// rfid_lecteur.print_UID();
			//rfid_lecteur.read(0x04, 0x04);
			//Serial.println(rfid_lecteur.UID_char);
			//String reponse_rfid = verifier_rfid(http_clt, rfid_lecteur.UID_char, BUILDING);
			//if (reponse_rfid == "0")
			//{
			//	Serial.println("RFID non ok");
			//	goto end_in;
			//}
			//else if (reponse_rfid == "-1") // C'est bien le tag d'un velo
			//{
			// Obtention du nom du vélo
			//rfid_lecteur.read(0x04, 0x04);
			//	Serial.println(rfid_lecteur.rfid_read_char);
			//}
			//else
			//{
			//	Serial.println("Tag client ok, prendre un velo");
			//	out_sans_reservation(reponse_rfid, http_clt, BUILDING);
			//	goto end_in;
			//}
			/////////////////////////////////////////
			////////////////////////////////////////

			Serial.println("RFID ok, vélo a ranger.");
			// Ouverture de l'armoire
			digitalWrite(SOLE_ARM_PIN, HIGH); // Actionner solenoides de la porte
			lcd.print_total("Ouvrir la porte");
			milli = millis();
			while (!digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis()) // On attend que soit la porte soit ouverte ou que le timeout soit depasse
			{
			}
			if ((milli + TIMEOUT) <= millis() && !digitalRead(CAPT_ARM_PIN)) // Le temps a depasse le timeout et la porte est toujours fermee, on bloque donc la porte
			{
			Serial.println("Temps écoulé");
			digitalWrite(SOLE_ARM_PIN, LOW);
			goto end_in;
			}

			Serial.println("La porte est bien ouverte");
			// Mise de la clé
			emplacement = mettre_cle(http_clt, MAX_EMPL, BUILDING);
			if (emplacement == -1)
			{
				Serial.println("Erreur dans la mise de clé");
				goto end_in;
			}
			// Fermeture de l'armoire
			lcd.print_total("Fermer la porte");
			milli = millis();
			while (digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis()) // On attend que soit la porte soit fermee ou que le timeout soit depasse
			{
			}
			if ((milli + TIMEOUT) <= millis() && digitalRead(CAPT_ARM_PIN)) // Le temps a depasse le timeout et la porte est toujours ouverte, on fait sonner l'alarme jusqu'a la fermeture
			{
				Serial.println("Temps écoulé");
				tone(BUZZ_PIN, 500);
				lcd.print_total("Fermer la porte", "Temps ecoule");
				while (digitalRead(CAPT_ARM_PIN)) // Tout est bloque en attendant que la porte soit fermee
				{
				}
			}
			noTone(BUZZ_PIN);
			digitalWrite(SOLE_ARM_PIN, LOW);
			Serial.println("La porte est bien fermée");
			// Dire que la clé est bien revenue pour ajouter info dans la base de donnees
			envoyer_cle_remise(http_clt, rfid_lecteur.rfid_read_char, emplacement, BUILDING);
			end_in:
			actual_mode = WAIT;
			lcd.print_menu();
		}
	}
	alarme_porte(3000, true, &timer_buzz_porte); // On test si la porte est ouverte alors qu'elle ne doit pas l'etre

	test_Ethernet(server, &timer_ethernet_error, &test_ethernet_error); // On test si on a toujours une connexion internet

}
/*--------------------------------------------------*/

/*
  * Lis 4 caracteres et place le tout dans un tableau "code"
  * Return TRUE si c'est ok
  * Return FALSE si le timeout est depasse
*/
bool entrer_code(char code[5]) // utilisee juste dans settings (menu *)
{
	unsigned long milli = millis();
	lcd.clear();
	lcd.print("Code : ");
	for (int i = 0; i < 4; i++)
	{
		char key_pre;
		do
		{
			key_pre = myKeypad.getKey();
		} while (!key_pre && (milli + TIMEOUT) > millis());
		code[i] = key_pre;
		lcd.print("*");
	}
	if ((milli + TIMEOUT) <= millis())
	{
		code = (char *)"-1";
		return false;
	}
	code[4] = '\0';
	lcd.setCursor(0, 1);
	lcd.print("Code recu : ");
	lcd.print(code);
	Serial.print("Code recu : ");
	Serial.println(code);
	return true;
}

/*
  * Lis 4 caracteres et place le tout dans un tableau "code"
  * Le premier caractere est passe par valeur car c'est celui qui est lu dans l'interruption clavier
  * Return TRUE si c'est ok
  * Return FALSE si le timeout est depasse
*/
bool entrer_code(char code[5], char first_number)
{
	unsigned long milli = millis();
	lcd.clear();
	lcd.print("Code : *");
	code[0] = first_number;
	for (int i = 1; i < 4; i++)
	{
		char key_pre;
		do
		{
			if (!digitalRead(CAPT_ARM_PIN))
			{
				key_pre = myKeypad.getKey();
			}
			//alarme_porte(50000, false, &timer_buzz_porte);
		} while (!key_pre && (milli + TIMEOUT) > millis());
		code[i] = key_pre;
		lcd.print("*");
	}
	if ((milli + TIMEOUT) <= millis())
	{
		code = (char *)"-1";
		return false;
	}
	code[4] = '\0';
	lcd.setCursor(0, 1);
	lcd.print("Code recu : ");
	lcd.print(code);
	Serial.print("Code recu : ");
	Serial.println(code);
	return true;
}

/*
  * Verifie si le code est bien dans la base de donnees
  * Return l'emplacement en cas de succes
  * Return -1 si la cle ne peut etre prise car la reservation est hors delai
  * Return -2 si le code a deja ete utilise ou la reservation a ete annulee
  * Return -3 si le code est inconnu
*/
int verifier_code(HttpClient clt, char code[5], const char building[]) // retourne l'emplacement
{
	int empl;

	char buf[200];
	sprintf(buf, "/include/lock/lock_verifier_code.php?code=%s&building=%s", code, building);
	Serial.println(buf);
	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	Serial.print("Response: ");
	Serial.println(response);
	if (statusCode == 200)
	{
		if (response == "-1") // hors délai
		{
			empl = -1;
			Serial.println("Hors délai");
			lcd.print_total("Hors delai");
			delay(2000);
		}
		else if (response == "-2") // code deja utilise ou annule
		{
			empl = -1;
			Serial.println("Code deja utilise ou annule");
			lcd.print_total("Deja utilise ou", "annule");
			delay(2000);
		}
		else if (response == "-3") // mauvais code
		{
			empl = -1;
			Serial.println("Mauvais code");
			lcd.print_total("Mauvais code");
			delay(2000);
		}
		else if (response != "")
		{
			empl = response.toInt();
		}
		else
		{
			empl = -1;
		}

		Serial.print("Emplacement : ");
		Serial.println(empl);
		return empl;
	}
	else
	{
		Serial.println("Connection failed.");
		return -1;
	}
}

/*
  * Routine pour enlever une cle dont l'emplacement est passe par valeur de l'armoire
  * Return TRUE si c'est ok
  * Return FALSE si le timeout est depasse sans que la cle soit prise
*/
bool enlever_cle(int empl)
{
	int empl_led_sole;
	int empl_switch;
	// if (empl > 5)
	// {
	// 	empl_led_sole = empl - 6;
	// 	empl_switch = empl - 6;
	// }
	// else
	// {
		empl_led_sole = empl - 1;
		empl_switch = empl - 1;
	// }
	output[8 + empl_led_sole] = 1; // Solenoide
	output[empl_led_sole] = 1;	 // LED
	out_74hc595.set_value(output);	// envoyer valeur dans le registre
	out_74hc595.print_value();
	unsigned long milli = millis();
	Serial.println("Moteur monté");
	switch_74hc165.get_value(input);
	while (input[empl_switch] == 1 && digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis())
	{
		switch_74hc165.get_value(input);
	}
	if ((milli + TIMEOUT) <= millis() || !digitalRead(CAPT_ARM_PIN))
	{
		Serial.println("Porte fermée - Erreur");
		output[8 + empl_led_sole] = 0; // Solenoide
		output[empl_led_sole] = 0;	 // LED
		out_74hc595.set_value(output);
		return false;
	}

	delay(4000); // La cle est expulsee de son compartiment que le solenoide se leve a cause de la pression du switch.
		// On laisse donc un delai raisonnable pour que l'utilisateur voit quelle cle il doit prendre
	Serial.println("Clé reprise");
	output[8 + empl_led_sole] = 0; // Solenoide
	output[empl_led_sole] = 0;	 // LED
	out_74hc595.set_value(output);
	Serial.println("Fermé");
	return true;
}

/*
  * Envoi l'information du succes de la prise de cle a la base de donnees
  * Return TRUE si c'est ok
  * Return FALSE si la connexion n'a pas su etre etablie
*/
bool envoyer_cle_prise_code(HttpClient clt, char code[5], int empl, const char building[])
{
	char buf[200];
	sprintf(buf, "/include/lock/lock_update_prise_cle.php?code=%s&building=%s&emplacement=%d", code, building, empl);
	Serial.println(buf);

	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	if (statusCode == 200)
	{
		return true;
	}
	else
	{
		return false;
	}
}
/*
  * Envoi l'information du succes de la prise de cle a la base de donnees
  * Return TRUE si c'est ok
  * Return FALSE si la connexion n'a pas su etre etablie
*/
bool envoyer_cle_prise_card(HttpClient clt, int empl, char UID[9], const char building[])
{
	char buf[200];
	sprintf(buf, "/include/lock/lock_update_prise_cle_card.php?UID=%s&building=%s&emplacement=%d", UID, building, empl);
	Serial.println(buf);

	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	if (statusCode == 200)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*
  * Envoi l'information du succes de la prise de cle a partir d'un tag a la base de donnees
  * Return TRUE si c'est ok
  * Return FALSE si la connexion n'a pas su etre etablie
  * !!!!     PAS UTILISE DANS CE CODE     !!!
*/
bool envoyer_cle_prise(HttpClient clt, String frame_number, int empl, const char building[], String email)
{
	char buf[200];
	sprintf(buf, "/include/lock/lock_update_prise_cle_tag.php?frame_number=%s&emplacement=%d&building=%s&email=%s", frame_number.c_str(), empl, building, email.c_str());
	Serial.println(buf);

	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	if (statusCode == 200)
	{
		return true;
	}
	else
	{
		return false;
	}
}


/*
  * Verifie si la carte est une carte velo ou client
  * Return VELO si RFID velo
  * Return CLIENT CARD si RFID client
  * Return 0 si la carte est vide d'information
*/
String verifier_rfid_card(char rfid[16], const char nom_velo[3]) 
{
	char nom_card[3] = {rfid[0],rfid[1],rfid[2]}; 

	char vide[3]= "";

		if (nom_card == vide) // Rien n'est disponible
		{
			Serial.println("RFID non ok");
			return "0";
		}
		else
		{
			if ( strcmp(nom_card, nom_velo)==0)
			{
				return "VELO";
			}
			else
			{
				return "CLIENT_CARD";
			}
	    }
	

}

/*
  * Verifie si le client est bien dans la DB et a une reservation
  * Return l'emplacement en cas de succes
  * Return empl = -1 en cas d'echec
  		* Reponse -1 si la cle ne peut etre prise car la reservation est hors delai
  	    * Reponse -2 si le velo a deja ete pris ou la reservation a ete annulee
 		* Reponse -3 si le client est inconnu
*/
int verifier_rfid_client(HttpClient clt,char uid[9], const char building[]) //A FAIRE
{
	int empl;

	char buf[200];
	sprintf(buf, "/include/lock/lock_verifier_rfid_client.php?uid=%s&building=%s", uid, building);
	Serial.println(buf);
	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	Serial.print("Response: ");
	Serial.println(response);
	if (statusCode == 200)
	{
		if (response == "-1") // hors délai
		{
			empl = -1; // 
			Serial.println("Hors délai");
			lcd.print_total("Hors delai");
			delay(2000);
		}
		else if (response == "-2") // réservation déja utilisée ou annulee
		{
			empl = -1;
			Serial.println("Reservation deja utilisee ou annulee");
			lcd.print_total("Reservation deja utilisee ou annulee");
			delay(2000);
		}
		else if (response == "-3") // client inconnu
		{
			empl = -1;
			Serial.println("Client inconnu");
			lcd.print_total("Client inconnu");
			delay(2000);
		}
		else if (response != "")
		{
			empl = response.toInt();
		}
		else
		{
			empl = -1;
		}

		Serial.print("Emplacement : ");
		Serial.println(empl);
		return empl;
	}
	else
	{
		Serial.println("Connection failed.");
		return -1;
	}
}

/*
  * Verifie si le velo est bien en voyage dans la base de donnees
  * Return TRUE si c'est ok
  * Return FALSE dans le cas contraire
*/
bool verifier_rfid_velo(HttpClient clt, char nom[16])
{
	int valeur_retournee_db;

	char buf[200];
	sprintf(buf, "/include/lock/lock_verifier_rfid.php?frame_number=%s", nom);
	Serial.println(buf);

	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	Serial.print("Response: ");
	Serial.println(response);
	if (statusCode == 200)
	{
		if (response == "-1") // Le velo est a l'emplacement -1 quand il est en voyage
		{
			valeur_retournee_db = response.toInt();
		}
		else
		{
			valeur_retournee_db = 0;
		}

		Serial.print("Valeur retournée : ");
		Serial.println(valeur_retournee_db);
		if (valeur_retournee_db == -1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		Serial.println("Connection failed.");
		return false;
	}
}

/*
  * Verifie si le velo est bien en voyage dans la base de donnees ou le tag utilisateur est dans la base de donnees
  * Return -1 si c'est ok
  * Return une chaine de caractere avec l'emplacement et le nom du velo si la reservation rapide est possible
  * Return 0 si rien n'est possible
  *  * !!!!     PAS UTILISE DANS CE CODE     !!!
*/
String verifier_rfid(HttpClient clt, char uid[9], const char building[]) // pas utilisee, pour badge societe
{
	char buf[200];
	sprintf(buf, "/include/lock/lock_verifier_rfid_2.php?uid=%s&building=%s", uid, building);
	Serial.println(buf);

	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	Serial.print("Response: ");
	Serial.println(response);
	if (statusCode == 200)
	{
		if (response == "") // Rien n'est disponible
		{
			Serial.println("RFID non ok");
			return "0";
		}
		else
		{
			Serial.print("Reponse : ");
			Serial.println(response);
			return response;
		}
	}
	else
	{
		Serial.println("Connection failed.");
		return "0";
	}
}

/*
  * Routine pour mettre une cle dans l'armoire
  * Return l'emplacement en cas de succes
  * Return -1 s'il n'y a pas d'emplacement libre, si le timeout est depasse
*/

/*
  * Routine pour mettre une cle dans l'armoire
  * Return l'emplacement en cas de succes
  * Return -1 s'il n'y a pas d'emplacement libre, si le timeout est depasse
*/
int mettre_cle(HttpClient clt, int max_empl, const char building[])
{
	Serial.println("Mettre cle");
	int emplacement = emplacement_libre(clt, max_empl, building);
	switch_74hc165.get_value(input);
	if (emplacement == -1)
	{
		Serial.println("Pas d'emplacement libre");
		return -1;
	}
	else
	{
		Serial.print("Emplacement libre en : ");
		Serial.println(emplacement);
		lcd.print_total("Mettre cle emplacement : ");
		lcd.print(emplacement);

		int empl_led_sole;
		int empl_switch;
		// if (emplacement > 5)
		// {
		// 	empl_led_sole = emplacement - 6;
		// 	empl_switch = emplacement - 6;
		// }
		// else
		// {
			empl_led_sole = emplacement - 1;
			empl_switch = emplacement - 1;
		// }

		output[8 + empl_led_sole] = 1; // Solenoide
		output[empl_led_sole] = 1;	 // LED
		out_74hc595.set_value(output);
		out_74hc595.print_value();
		Serial.println("Moteur monté");
		switch_74hc165.get_value(input);
		unsigned long milli = millis();
		while (input[empl_switch] == 0 && digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis())
		{
			switch_74hc165.get_value(input);
		}
		if ((milli + TIMEOUT) <= millis() || !digitalRead(CAPT_ARM_PIN)) // Si on ferme la porte
		{
			Serial.println("Porte fermée - Erreur");
			output[8 + empl_led_sole] = 0; // Solenoide
			output[empl_led_sole] = 0;	 // LED
			out_74hc595.set_value(output);
			return -1;
		}
		/*if ((milli + TIMEOUT) <= millis() || digitalRead(CAPT_ARM_PIN))  // Si le timeout est passé et que la porte est ouverte
    {
      Serial.println("Clé non mise");
      tone(BUZZ_PIN, 500);
      lcd.clear();
      lcd.print("Cle mise ?");
      lcd.setCursor(0,1);
      lcd.print("OUI = 1");
      lcd.setCursor(0,2);
      lcd.print("NON = 0");
      char key_pre;
      while(key_pre != '1' && key_pre != '0')
      {
        key_pre = myKeypad.getKey();
      }
      if(key_pre == '1')
      {
        Serial.println("1");
      }
      else if(key_pre == '0')
      {
        Serial.println("0");
      }
      output[10 + emplacement - 1] = 0; // Solenoide
      output[emplacement - 1] = 0;      // LED
      out_74hc595.set_value(output);
      return -1;
    }*/
		Serial.println("Clé mise");
		output[8 + empl_led_sole] = 0; // Solenoide
		output[empl_led_sole] = 0;	 // LED
		out_74hc595.set_value(output);
		Serial.println("Fermé");
		return emplacement;
	}
}

/*
  * Questionne la base de donnees pour voir quel emplacement est libre dans l'armoire
  * Return le premier emplacement libre en cas de succes
  * Return -1 si la connexion n'a pas ete etablie ou s'il n'y a pas d'emplacement libre dans l'armoire
*/
int emplacement_libre(HttpClient clt, int max_empl, const char building[])
{
	char buf[200];
	sprintf(buf, "/include/lock/lock_emplacement_libre_2.php?max_empl=%d&building=%s", max_empl, building);
	Serial.println(buf);

	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	Serial.print("Response: ");
	Serial.println(response);
	if (statusCode == 200)
	{
		return response.toInt();
	}
	else
	{
		Serial.println("Connection failed.");
		return -1;
	}
}

/*
  * Envoi l'information du succes de la remise de cle a la base de donnees
  * Return TRUE si c'est ok
  * Return FALSE si la connexion n'a pas su etre etablie
*/
bool envoyer_cle_remise(HttpClient clt, char nom[16], int empl, const char building[])
{
	char buf[200];
	sprintf(buf, "/include/lock/lock_update_remise_cle.php?emplacement=%d&frame_number=%s&building=%s", empl, nom, building);
	Serial.println(buf);

	clt.get(buf);
	// read the status code and body of the response
	int statusCode = clt.responseStatusCode();
	String response = clt.responseBody();

	Serial.print("Status code: ");
	Serial.println(statusCode);
	if (statusCode == 200)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*
  * Effectue une sortie de velo sans reservation
  * Return TRUE si c'est ok
  * Return FALSE si c'est pas ok
  * !!!!     PAS UTILISE DANS CE CODE     !!!
*/
bool out_sans_reservation(String rep, HttpClient clt, const char building[]) // Si bagde entreprise, pas utilisee
{
	unsigned long milli;
	int first_char = rep.indexOf(':');
	int second_char = rep.indexOf(':', first_char + 1);
	String frame_num = rep.substring(0, first_char);
	int empl = rep.substring(first_char + 1, second_char).toInt();
	String email = rep.substring(second_char + 1);
	Serial.print("Frame number : ");
	Serial.println(frame_num);
	Serial.print("Empl : ");
	Serial.println(empl);
	Serial.print("Email : ");
	Serial.println(email);
	// Ouverture de l'armoire
	digitalWrite(SOLE_ARM_PIN, HIGH); // Actionner solenoides de la porte
	lcd.print_total("Ouvrir la porte");
	milli = millis();
	while (!digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis()) // On attend que soit la porte soit ouverte ou que le timeout soit depasse
	{
	}
	if ((milli + TIMEOUT) <= millis() && !digitalRead(CAPT_ARM_PIN)) // Le temps a depasse le timeout et la porte est toujours fermee, on bloque donc la porte
	{
		Serial.println("Temps ecoule");
		digitalWrite(SOLE_ARM_PIN, LOW);
		return false;
	}
	Serial.println("La porte est bien ouverte");
	// Deverrouillage bonne cle
	lcd.print_total("Prendre cle", "Emplacement : ");
	lcd.print(empl);
	if (!enlever_cle(empl))
	{
		Serial.println("Erreur dans la prise de clé");
		return false;
	}
	Serial.println("Prise de clé reussie");
	// Fermeture de l'armoire
	lcd.print_total("Fermer la porte");
	milli = millis();
	while (digitalRead(CAPT_ARM_PIN) && (milli + TIMEOUT) > millis()) // On attend que soit la porte soit ouverte ou que le timeout soit depasse
	{
	}
	if ((milli + TIMEOUT) <= millis() && digitalRead(CAPT_ARM_PIN)) // Le temps a depasse le timeout et la porte est toujours ouverte, on fait sonner l'alarme jusqu'a la fermeture
	{
		Serial.println("Temps écoulé");
		tone(BUZZ_PIN, 500);
		lcd.print_total("Fermer la porte", "Temps ecoule");
		while (digitalRead(CAPT_ARM_PIN)) // Tout est bloque en attendant que la porte soit fermee
		{
		}
	}
	noTone(BUZZ_PIN);
	digitalWrite(SOLE_ARM_PIN, LOW);
	Serial.println("La porte est bien fermée");
	// Envoyer message pour dire que la cle est bien prise et adapter la base de donnees
	envoyer_cle_prise(clt, frame_num, empl, building, email);
	return true;
}

/*
  * Verifie si la porte n'est pas ouverte alors qu'elle ne doit pas l'etre
  * Si c'est le cas l'alarme retenti et l'armoire est bloquee
  * Le temps est le nombre de cycle avant que l'alarme retentisse
  * Affiche_menu dit si a la fin de l'alarme il faut reafficher le menu ou non
*/
void alarme_porte(int temps, bool affiche_menu, int *tmr)
{
	if (digitalRead(CAPT_ARM_PIN))
	{
		if (*tmr == 0)
		{
			lcd.print_total("ATTENTION", "Fermer porte");
		}
		*tmr = *tmr + 1;
		if (*tmr > temps)
		{
			tone(BUZZ_PIN, 500);
		}
	}
	else
	{
		noTone(BUZZ_PIN);
		if (*tmr > 0 && affiche_menu)
		{
			lcd.print_menu();
		}
		*tmr = 0;
	}
}

/*
  * Test toutes les 10 secondes si on sait bien avoir une connexion au site internet.
  * Si la connexion est impossible 3 fois d'affilees, l'Arduino redemarre
*/
void test_Ethernet(const char srv[], unsigned long *tmr, int *compteur_erreur) // Voir ce qu'il se passe au moment de l'overflow
{
	if (*tmr < millis())
	{
		*tmr = millis() + 10000;
		EthernetClient clt;
		if (clt.connect(srv, 80) != 1)
		{
			*compteur_erreur = *compteur_erreur + 1;
			Serial.print("Pas de réseau : ");
			Serial.println(*compteur_erreur);
			delay(50);
			if (*compteur_erreur > 2)
			{
				wdt_enable(WDTO_15MS);
				while (1)
				{
				}
			}
		}
		else
		{
			*compteur_erreur = 0;
		}
		clt.stop();
	}
}

/*
  * Affiche le menu deroulant sur l'ecran en fonction de selected_step
*/
void menu_lcd_changer_etat(step selected)
{
	switch (selected)
	{
	case Sortir:
		lcd.print_total("", "Sortir <--", "Sol_ext");
		break;
	case Sol_ext:
		lcd.print_total("Sortir", "Sol_ext <--", "Capt_ext");
		break;
	case Capt_ext:
		lcd.print_total("Sol_ext", "Capt_ext <--", "RFID_UID");
		break;
	case RFID_UID:
		lcd.print_total("Capt_ext", "RFID_UID <--", "Mettre_cle");
		break;
	case Mettre_cle:
		lcd.print_total("RFID_UID", "Mettre_cle <--", "Test_sol");
		break;
	case Test_sol:
		lcd.print_total("Mettre_cle", "Test_sol <--", "Test_LED");
		break;
	case Test_LED:
		lcd.print_total("Test_sol", "Test_LED <--", "RFID_read");
		break;
	case RFID_read:
		lcd.print_total("Test_LED", "RFID_read <--", "RFID_write");
		break;
	case RFID_write:
		lcd.print_total("RFID_read", "RFID_write <--", "Enlever_cle");
		break;
	case Enlever_cle:
		lcd.print_total("RFID_write", "Enlever_cle <--", "LCD_test");
		break;
	case LCD_test:
		lcd.print_total("Enlever_cle", "LCD_test <--", "Code");
		break;
	case Code:
		lcd.print_total("LCD_test", "Code <--", "Etape_12");
		break;

	default:
		break;
	}
}

/*
  * Effectue l'action de used_step quand on le demande
*/
void menu_lcd_action(step used)
{
	switch (used)
	{
	case Sortir:
	{
		Serial.println("Sortir");
		actual_mode = WAIT;
		lcd.print_menu();
		break;
	}
	case RFID_read:
	{
		Serial.println("RFID read");
		rfid_lecteur.read(0x04, 0x04);
		Serial.println(rfid_lecteur.rfid_read_char);
		break;
	}
	case RFID_write:
	{
		Serial.println("RFID write");
		char nom[16] = "VEN-203";
		rfid_lecteur.writeUL(0x04, 0x04, nom);
		break;
	}
	case RFID_UID:
	{
		Serial.println("RFID UID");
		rfid_lecteur.print_UID();
		lcd.print(rfid_lecteur.UID_char);
		delay(1000);
		break;
	}
	case Mettre_cle:
	{
		Serial.println("Mettre clé test");
		lcd.print_total("Emplacement : ");
		char key_pre;
		do
		{
			key_pre = myKeypad.getKey();
		} while (!key_pre);
		int emplacement = key_pre - '0';
		if (emplacement == 0)
			emplacement = 10;
		lcd.print(emplacement);
		Serial.print("Emplacement : ");
		Serial.println(emplacement);

		int empl_led_sole;
		int empl_switch;
		// if (emplacement > 5)
		// {
		// 	empl_led_sole = emplacement - 6;
		// 	empl_switch = emplacement - 6;
		// }
		// else
		// {
			empl_led_sole = emplacement - 1;
			empl_switch = emplacement - 1;
		// }

		output[8 + empl_led_sole] = 1; // Solenoide
		output[empl_led_sole] = 1;	 // LED
		out_74hc595.set_value(output);
		out_74hc595.print_value();
		Serial.println("Moteur monté");
		switch_74hc165.get_value(input);
		while (input[empl_switch] == 0)
		{
			switch_74hc165.get_value(input);
		}
		Serial.println("Clé mise");
		output[8 + empl_led_sole] = 0; // Solenoide
		output[empl_led_sole] = 0;	 // LED
		out_74hc595.set_value(output);
		Serial.println("Fermé");
		break;
	}
	case Enlever_cle:
	{
		Serial.println("Enlever clé test");
		lcd.print_total("Emplacement : ");
		char key_pre;
		do
		{
			key_pre = myKeypad.getKey();
		} while (!key_pre);
		int emplacement = key_pre - '0';
		if (emplacement == 0)
			emplacement = 10;
		lcd.print(emplacement);
		Serial.print("Emplacement : ");
		Serial.println(emplacement);

		enlever_cle(emplacement);

		break;
	}
	case LCD_test:
	{
		Serial.println("Test lcd");
		lcd.clear();
		lcd.print("IP: test");
		lcd.print(millis());
		//lcd.print(Ethernet.localIP());
		break;
	}
	case Code:
	{
		Serial.println("Code");
		entrer_code(keypad_code);
		break;
	}
	case Test_sol:
	{
		Serial.println("Test_sol");
		lcd.print_total("Emplacement : ");
		char key_pre;
		do
		{
			key_pre = myKeypad.getKey();
		} while (!key_pre);
		int emplacement = key_pre - '0';
		if (emplacement == 0)
			emplacement = 10;
		lcd.print(emplacement);
		Serial.print("Emplacement : ");
		Serial.println(emplacement);

		// int empl_led_sole;
		// if (emplacement > 5)
		// {
		// 	empl_led_sole = emplacement - 6;
		// }
		// else
		// {
		// 	empl_led_sole = 16 + emplacement - 1;
		// }

		output[8 + emplacement-1] = 1; // Solenoide
		out_74hc595.set_value(output);
		out_74hc595.print_value();
		Serial.println("Moteur monté");
		delay(5000);
		output[8 + emplacement-1] = 0; // Solenoide
		out_74hc595.set_value(output);
		out_74hc595.print_value();
		Serial.println("Moteur descendu");
		break;
	}
	case Test_LED:
	{
		Serial.println("Test_LED");
		lcd.print_total("Emplacement : ");
		char key_pre;
		do
		{
			key_pre = myKeypad.getKey();
		} while (!key_pre);
		int emplacement = key_pre - '0';
		if (emplacement == 0)
			emplacement = 10;
		lcd.print(emplacement);
		Serial.print("Emplacement : ");
		Serial.println(emplacement);

		// int empl_led_sole;
		// if (emplacement > 5)
		// {
		// 	empl_led_sole = emplacement - 6;
		// }
		// else
		// {
		// 	empl_led_sole = 16 + emplacement - 1;
		// }

		output[emplacement-1] = 1; // LED
		out_74hc595.set_value(output);
		out_74hc595.print_value();
		Serial.println("LED allumee");
		delay(20000);
		output[emplacement-1] = 0; // LED
		out_74hc595.set_value(output);
		out_74hc595.print_value();
		Serial.println("LED eteinte");
		break;
	}
	case Capt_ext:
	{
		Serial.println("Capt_ext");
		if (!digitalRead(CAPT_ARM_PIN))
		{
			lcd.print_total("Etat capteur : ", "Fermee");
		}
		else
		{
			lcd.print_total("Etat capteur : ", "Ouverte");
		}
		break;
	}
	case Sol_ext:
	{
		Serial.println("Sol_ext");
		lcd.print_total("Sol_ext capteur : ");
		digitalWrite(SOLE_ARM_PIN, HIGH);
		delay(5000);
		digitalWrite(SOLE_ARM_PIN, LOW);
	}

	default:
		break;
	}
}