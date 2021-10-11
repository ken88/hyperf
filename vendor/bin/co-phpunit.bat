@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../hyperf/testing/co-phpunit
php "%BIN_TARGET%" %*
