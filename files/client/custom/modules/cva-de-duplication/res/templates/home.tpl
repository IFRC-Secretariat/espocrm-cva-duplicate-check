<div class="page-header">
    <h2>SARC CVA Duplication Check Platform</h2>
</div>
<div class="home-content">
    <p>
        This platform is managed by <a href="https://sarc.sy">The Syrian Arab Red Crescent</a>, and is for collaboration and duplicate checking on cash activities between partners in Syria. It can be used for uploading data on cash distributions, and running duplicate checks. 
        Click the buttons to upload cash distribution data or run a duplicate check. See below for more information.
    </p>
    <a role="button" tabindex="0" class="btn btn-default btn-xs-wide main-header-manu-action action" data-name="importCashDistributionData" data-action="importCashDistributionData" data-handler="cva-de-duplication:import-cash-distribution-data">
        <span class="fas fa-file-upload fa-sm"></span>
        Upload Cash Distribution data
    </a>
    <a role="button" tabindex="0" class="btn btn-default btn-xs-wide main-header-manu-action action radius-right" data-name="importDuplicateCheckData" data-action="importDuplicateCheckData" data-handler="cva-de-duplication:import-duplicate-check-data">
        <span class="fas fa-network-wired fa-sm"></span>
        Check duplicates
    </a>
    <a href="#Import/list" class="btn btn-default btn-xs-wide main-header-manu-action action radius-left">
        View previous Cash Distributions and Duplicate Checks
    </a>

    <h3>Uploading Cash Distribution data</h3>
    <p>
        It is important to upload data into the platform after carrying out a cash distribution. This is so that when other organisations run duplicate checks, they can see who has already received cash.
    </p>

    <ol class="instructions">
        <li>Upload data: the cash distribution data that you upload should contain a column with National IDs of the person (or head of household) who received the cash. You can also include data on governorate, date of transfer, and transfer value:
        <table class="table data-example">
        <tr>
            <th>National ID<span class="required"> * (required)</span><p class="subtitle">Syrian National ID of the person (or HoH) who received cash (11 digits)</p></th>
            <th>Governorate<p class="subtitle">Governorate in Syria</p></th>
            <th>Date<p class="subtitle">Date of the transfer - all the dates must be in the same format</p></th>
            <th>Transfer value<p class="subtitle">Amount of the transfer in Syrian Pounds (SYP)</p></th>
        </tr>
        <tr>
            <td>92846103678</td>
            <td>Homs</td>
            <td>06/11/2023</td>
            <td>600000</td>
        </tr>
        <tr>
            <td>23846287365</td>
            <td>Damascus</td>
            <td>30/10/2023</td>
            <td>800000</td>
        </tr>
        </table>
        </li>

        <li>Set properties: enter the property options, including field delimeter, date format, decimal mark, and text qualifier. Check in the preview that the data displays correctly. Click Next.</li>

        <li>Select columns: select the column names to match up to the system column names.</li>

        <li>Results: the results of the import show:
        <table class="table cash-distribution-results">
        <tr class="imported">
            <th>Imported</th>
            <th>This data was successfully imported and saved in the system.</th>
        </tr>
        <tr class="duplicates">
            <td>Duplicates</td>
            <td>This data was saved in the system, but is a duplicate of other cash distribution data. This means that either you or another organisation has already given this person cash.</td>
        </tr>
        <tr class="errors">
            <td>Errors</td>
            <td>This data was NOT saved in the system because it contains errors. E.g., the National ID is not 11 digits, the date format is incorrect, the transfer value is not a number, etc. Click on each error for more details.</td>
        </tr>
        </table>
        <p>
            If needed, you can revert the import by clicking the "Revert Import" button at the top.
        </p>
        </li>
    </ol>
</div>
