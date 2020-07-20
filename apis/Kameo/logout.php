<?php
  session_cache_limiter('nocache');
  header('Expires: ' . gmdate('r', 0));
  header('Content-type: application/json');
  session_start();
  $_SESSION = array();
  session_destroy();
  echo json_encode($_SESSION);
