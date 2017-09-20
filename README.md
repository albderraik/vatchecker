## GOAL
Write a script/cli application to validate/verify VAT numbers.
See https://en.wikipedia.org/wiki/VAT_identification_number for what a VAT number is.

Validating is aginst a SOAP based webservice provided by the European Comission, see http://ec.europa.eu/taxation_customs/vies/

Some technical information about the service is at http://ec.europa.eu/taxation_customs/vies/technicalInformation.html

WDSL is at: http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl 
the service (checkVatService) end point we're interested in is at: http://ec.europa.eu/taxation_customs/vies/services/checkVatService

## Requirements
- You can use any language you like, either a scripting or compiled language, doesn't matter, up to you
- Include any special instructions for compilation (if needed)
- A link to a GitHub repo/gist with the solution is preferred (if you'd like to keep it private, share it with @refiito at GitHub)
- The script/cli application should take a VAT number as the single argument
- Output should be either "Valid" or "Invalid" or, in a case of some exception/error (from the SOAP service), said exception/error

## Installation
This script use PHP lang.

### Needed Packages
- php-fastcgi

### To Run

http://server/vatchecker/?vat=IT06700351213


