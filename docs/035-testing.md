# Testing

This file contains information about how to test the extension, including running automated EspoCRM unit and integration tests, and testing manually.

## Automated testing: unit and integration tests

To run EspoCRM unit and integration tests: go to [Run unit and integration tests](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/actions/workflows/run-tests.yml) in `Actions`. Click the `Run workflow` button. You can see the progress of the tests under [Actions](https://github.com/IFRC-Secretariat/espocrm-cva-duplicate-check/actions).

This runs EspoCRM tests, but does not test functionality specific to this extension.


## Manual testing

This testing assumes testing from a blank installation, with this extension installed. You can skip steps if you already have them.

Set up the tests by doing the following:

1. Create two Partners, e.g. with names `SARC` and `WFP`. Set their roles to `Partner`.

2. Create two users with the following info, one assigned to each Partner. Click `Save`.

    | User name | Email | Type | Is Active | Partners | Password | Send Email with Access Info to User |
    | -------- | ------- | ------- | ------- | ------- | ------- | ------- | 
    | sarc_user | sarc@test.org | Regular | ✔ | SARC | 5888IHBKOQmWernj | x |
    | wfp_user | wfp@test.org | Regular | ✔ | WFP | 44NHX9swGHZ4641x | x |


### Test user required fields and password

1. Go to the `Create user` form.

2. Check that the following fields are required fields, by submitting the form without them. This should give a `Not valid` error.

    - User name
    - Email
    - Partners

3. Add the following information:

    | User name | Email | Type | Is Active | Partners |
    | -------- | ------- | ------- | ------- | ------- | 
    | test_user | test@test.org | Regular | ✔ | SARC |

4. Set the password to "password" and click `Save`. This should give a `Not valid` error, and show the message `Must be at least 16 characters long.` on the password field.

5. Set the password to "passwordpassword" and click `Save`. This should give a `Not valid` error, and show the message `Must contain letters of both upper and lower case.` on the password field.

6. Set the password to "passwordPassword" and click `Save`. This should give a `Error 403: Access denied Password is weak.` error.

7. Set the password to "passwordPassword123" and click `Save`. This should give a `Error 403: Access denied Password is weak.` error.

8. Set the password to "passwordPassword1234" and click `Save`. This should save successfully.

9. Delete the user.


### Test cash distribution errors

1. Login as `sarc_user`.

2. Go to `Tools`, and upload the data in `data/cash_distributions_errors.csv`. Set the following information, and click `Next`.

    | Header row | Field Delimiter | Date Format | Decimal Mark | Text Qualifier | Currency |
    | -------- | ------- | ------- | ------- | ------- | ------- | 
    | ✔ | , | DD/MM/YYYY | . | Double Quote | SYP |

    ![Import step 1 settings](img/test_cash_distribution_errors_import_step1.png)

3. Set the field mapping, and click `Run Import`.

    ![Import step 2 settings](img/test_cash_distribution_errors_import_step2.png)

4. The results should show 1 successfully imported row, and 5 errors:

    ![Import results](img/test_cash_distribution_errors_import_results.png)

5. Check that each error contains the following validation failures:

    | Error Line Number | Field | Validation | 
    | -------- | ------- | ------- | 
    | 2 | National ID | Pattern Matching | 
    | 3 | National ID | Pattern Matching | 
    | 4 | Transfer Value | Valid | 
    | 5 | Transfer Value | Valid | 
    | 6 | Date | Valid | 

6. Remove all `Cash Distribution` data and imports.


### Test duplicate check errors

1. Login as `sarc_user`.

2. Go to `Tools`, and upload the data in `data/duplicate_check_errors.csv`. Set the following information, and click `Next`.

    | Header row | Field Delimiter | Text Qualifier |
    | -------- | ------- | ------- | 
    | ✔ | , | Double Quote | 

    ![Import step 1 settings](img/test_duplicate_check_errors_import_step1.png)

3. Set the field mapping, and click `Run Import`.

    ![Import step 2 settings](img/test_duplicate_check_errors_import_step2.png)

4. The results should show 1 successfully imported row, and 2 errors:

    ![Import results](img/test_duplicate_check_errors_import_results.png)

5. Check that each error contains the following validation failures:

    | Error Line Number | Field | Validation | 
    | -------- | ------- | ------- | 
    | 2 | National ID | Pattern Matching | 
    | 3 | National ID | Pattern Matching | 

6. Remove all imports.