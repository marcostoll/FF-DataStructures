@ECHO OFF
SET PROJECT_HOME=C:\dev\ffe\fastforward\DataStructures
vendor\bin\phpcs --report=xml --report-file="%PROJECT_HOME%\logs\phpcs\report.xml" --standard=PSR2 %PROJECT_HOME%\src %PROJECT_HOME%\tests -p