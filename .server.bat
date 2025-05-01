@echo off
echo Selecione a versao do PHP que deseja rodar:
echo 5 - PHP 5.6.9
echo 7 - PHP 7.4.6
echo 8 - PHP 8.2.7
set /p version="Digite o numero da versao do PHP para rodar: "

set phpPath=
set phpPort=

if "%version%"=="5" (
    set phpPath=_php\php-5.6.9\php.exe
    set phpPort=8005
) else if "%version%"=="7" (
    set phpPath=_php\php-7.4.6\php.exe
    set phpPort=8007
) else if "%version%"=="8" (
    set phpPath=_php\php-8.2.7\php.exe
    set phpPort=8008
) else (
    echo Versao invalida selecionada.
    pause
    exit /b
)

if exist "%phpPath%" (
    "%phpPath%" -S localhost:%phpPort%
) else (
    echo O executavel PHP nao foi encontrado no caminho "%phpPath%".
)

pause
