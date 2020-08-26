<script async src="https://www.googletagmanager.com/gtag/js?id=UA-108429655-1"></script>
<script type="text/javascript">
	const authorization = localStorage.getItem("ConsentAuthorization");

	if (authorization === 'accepted') {
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', 'UA-108429655-1');
	}
</script>