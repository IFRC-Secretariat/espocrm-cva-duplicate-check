# Usage Instructions

## Users and roles

Users can use the platform to run duplicate checks and upload data on cash distributions. All users should belong to a `Partner`, which should be the organisation name, e.g. WFP, UNICEF, IFRC, etc. To create a partner, click `Partners` in the left menu, click `Create Partner`, and set the `Name` to the organisation name, and set `Roles` to `Partner`. The `Partner` role is created automatically when the extension is installed, and gives users access to:

- Create Cash Distributions and run Duplicate Checks (including importing data)
- View and edit Cash Distributions and Duplicate Checks **of their partner only**
- Delete Cash Distributions **of their partner only**
- View users **of their partner only**

## Uploading cash distributions

It is important to **always** upload data into the platform **as soon as possible** after carrying out a cash distribution. This is so that when other organisations run duplicate checks, they can see who has already received cash. Follow these steps to upload cash distribution data:

1. Upload data: the cash distribution data that you upload should contain a column with National IDs of the person (or head of household) who received the cash. You can also include data on governorate, date of transfer, and transfer value:

    | National ID * (required) | Governorate | Date | Transfer value |
    | -------- | ------- | ------- | ------- |
    | 92846103678 | Homs | 06/11/2023 | 600000 |
    | 23846287365 | Damascus | 30/10/2023 | 800000 |

2. Set properties: enter the property options, including field delimeter, date format (e.g. in the data above this would be `DD/MM/YYYY`), decimal mark, and text qualifier. Check in the preview that the data displays correctly. Click `Next`.

3. Select columns: select the column names to match up to the system column names.

4. Results: the results of the import show:

    - **Imported**: This data was successfully imported and saved in the system.
    - **Duplicates**: This data was saved in the system, but is a duplicate of other cash distribution data. This means that either you or another organisation has already given these people cash.
    - **Errors**: This data was NOT saved in the system because it contains errors. E.g., the National ID is not 11 digits, the date format is incorrect, the transfer value is not a number, etc. Click on each error for more details.

    If needed, you can revert the import by clicking the "Revert Import" button at the top.


## Running duplicate checks

When planning a cash distribution, you can use this system to check whether applicants have already received cash. The comparison is done based on National IDs. Follow these steps to run a duplicate check:

1. Upload data: the cash distribution data that you upload should contain a column with National IDs of the person (or head of household) to duplicate check.

    | National ID * (required) | 
    | -------- |
    | 92846103678 |
    | 83764510394 |

2. Set properties: enter the property options, including field delimeter, and text qualifier. Check in the preview that the data displays correctly. Click Next.

3. Select National ID column: select the National ID column name.

4. Results: the results of the import show:

    - **Data no duplicates**: These people are not in the system so are not recorded as having received cash - you can go ahead and pay them.
    - **Duplicates**: This data is a duplicate of other cash distribution data. This means that either you or another organisation has already given these people cash.
    - **Errors**: This data was NOT duplicate checked because it contains errors. E.g., the National ID is not 11 digits, the date format is incorrect, the transfer value is not a number, etc. Click on each error for more details.

    If needed, you can revert the import by clicking the "Revert Import" button at the top.