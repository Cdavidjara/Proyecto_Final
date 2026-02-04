<?php
require_once __DIR__ . '/../app/Infrastructure/Security/AuthSession.php';
AuthSession::logout();
header('Location: login.php');
exit;
