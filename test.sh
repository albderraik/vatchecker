#!/bin/bash

x=`curl -s http://localhost/vatchecker/?vat=SS000` && [ $x == "INVALID_INPUT" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=CZ28987373` && [ $x == "Valid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=DE296459264` && [ $x == "Valid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=DE292188391` && [ $x == "Valid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=SE556900620701` && [ $x == "Invalid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=NL802465602B01` && [ $x == "Valid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=NL151412984B01` && [ $x == "Valid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=GB163980581` && [ $x == "Invalid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=PL9492191021` && [ $x == "Valid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=CZ64610748` && [ $x == "Valid" ] && echo Passed || echo Fail
x=`curl -s http://localhost/vatchecker/?vat=IT06700351213` && [ $x == "Valid" ] && echo Passed || echo Fail
