@if($section == 'contact')
    <table>
        <thead>
            <tr>
                <th width="30"></th>
                <th width="30"></th>
                <th width="30"></th>
                <th width="30"></th>
                <th width="30"></th>
                <th width="30"></th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td style="color: #357EC7;font-weight: 500;">Name and Address</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>

        <tr>
            <td>
                Contact ID
            </td>
            <td style="text-align: left;">
                {{ $record['DS_MKC_ContactID'] }}
            </td>
            <td>
                Household ID
            </td>
            <td style="text-align: left;">
                {{ $record['ds_mkc_householdid'] }}
            </td>
            <td>
                Extended Name
            </td>
            <td style="text-align: left;">
                {{ $record['Extendedname'] }}
            </td>
        </tr>


        <tr>
            <td>
                Salutation
            </td>
            <td style="text-align: left;">
                {{ $record['Salutation'] }}
            </td>
            <td>
                Person 2 Salutation
            </td>
            <td style="text-align: left;">
                {{ $record['Salutation2'] }}
            </td>
            <td>Letter Salutation</td>
            <td style="text-align: left;">
                {{ $record['LetterName'] }}
            </td>
        </tr>

        <tr>
            <td>
                Dharma Name
            </td>
            <td style="text-align: left;">
                {{ $record['DharmaName'] }}
            </td>
            <td>
                Person 2 Dharma Name
            </td>
            <td style="text-align: left;">
                {{ $record['DharmaName2'] }}
            </td>
            <td>Address</td>
            <td style="text-align: left;">
                {{ $record['Address'] }}
            </td>
        </tr>

        <tr>

            <td>
                First Name
            </td>
            <td style="text-align: left;">
                {{ $record['FirstName'] }}
            </td>

            <td>
                Person 2 First Name
            </td>
            <td style="text-align: left;">
                {{ $record['Firstname2'] }}
            </td>
            <td>City</td>
            <td style="text-align: left;">
                {{ $record['City'] }}
            </td>
        </tr>

        <tr>
            <td>
                Middle Name
            </td>
            <td style="text-align: left;">
                {{ $record['MiddleName'] }}
            </td>

            <td>
                Person 2 Middle Name
            </td>
            <td style="text-align: left;">
                {{ $record['Middlename2'] }}
            </td>
            <td>State</td>
            <td style="text-align: left;">
                {{ $record['State'] }}
            </td>
        </tr>

        <tr>
            <td>
                Last Name
            </td>
            <td style="text-align: left;">
                {{ $record['lastname'] }}
            </td>

            <td>
                Person 2 Last Name
            </td>
            <td style="text-align: left;">
                {{ $record['lastname2'] }}
            </td>
            <td>Zip</td>
            <td style="text-align: left;">
                {{ $record['Zip'] }}
            </td>
        </tr>

        <tr>
            <td>
                Suffix
            </td>
            <td style="text-align: left;">
                {{ $record['suffix'] }}
            </td>

            <td>
                Person 2 Suffix
            </td>
            <td style="text-align: left;">
                {{ $record['suffix2'] }}
            </td>
            <td>Country</td>
            <td style="text-align: left;">
                {{ $record['Country'] }}
            </td>
        </tr>

        <tr>
            <td>
                Gender
            </td>
            <td style="text-align: left;">
                {{ $record['gender'] }}
            </td>


            <td>Person 2 Gender</td>
            <td style="text-align: left;">
                {{ $record['Gender2'] }}
            </td>

            <td>Company Name</td>
            <td style="text-align: left;">
                {{ $record['Company'] }}
            </td>
        </tr>

        <tr>
            <td>
                Job Title
            </td>
            <td style="text-align: left;">
                {{ $record['JobTitle'] }}
            </td>

            <td>
                Persons In HH
            </td>
            <td style="text-align: left;">
                {{ $record['DS_MKC_Household_Num'] }}
            </td>

            <td>Company Include</td>
            <td style="text-align: left;">
                {{ $record['companyinclude'] }}
            </td>
        </tr>

        <tr>
            <td>
                DFLName
            </td>
            <td style="text-align: left;">
                {{ $record['DFLName'] }}
            </td>

            <td>
                Person 2 DFLName
            </td>
            <td style="text-align: left;">
                {{ $record['DFLName2'] }}
            </td>

            <td>Address Quality</td>
            <td style="text-align: left;">
                {{ $record['AddressQuality'] }}
            </td>
        </tr>



        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td style="color: #357EC7;
    font-weight: 500;">Contactability</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td>
                Email - Primary
            </td>
            <td style="text-align: left;">
                {{ $record['Email'].' ' .$record['Opt_Email'] }}
            </td>

            <td>
                Phone - Primary
            </td>
            <td style="text-align: left;">
                {{ $record['phone'].' ' .$record['phone_type'] }}
            </td>

            <td>Mail Status</td>
            <td style="text-align: left;">
                {{ $record['Opt_Mail'].' ' .$record['mail_status'] }}
            </td>
        </tr>

        <tr>
            <td>
                Email - Secondary
            </td>
            <td style="text-align: left;">
                {{ $record['Email2'].' ' .$record['opt_email2'] }}
            </td>

            <td>
                Phone - Secondary
            </td>
            <td style="text-align: left;">
                {{ $record['Phone2'].' ' .$record['Phone2_type'] }}
            </td>

            <td>Suppression</td>
            <td style="text-align: left;">
                {{ $record['Suppression'] }}
            </td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td style="color: #357EC7;font-weight: 500;">Key Events &amp; Segment</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td>
                First Sesshin Date
            </td>
            <td style="text-align: left;">
                {{ $record['First_Sesshin_Date'] }}
            </td>

            <td>
                Jukai Date
            </td>
            <td style="text-align: left;">
                {{ $record['Jukai_Date'] }}
            </td>

            <td>Ordainment Date</td>
            <td style="text-align: left;">
                {{ $record['Ordainment_Date'] }}
            </td>
        </tr>

        <tr>
            <td>
                ZSS Segment
            </td>
            <td style="text-align: left;">
                {{ $record['ZSS_Segment'] }}
            </td>

            <td>
                Member Segment
            </td>
            <td style="text-align: left;">
                {{ $record['MemberSegment'] }}
            </td>
            <td>Source Feed</td>
            <td style="text-align: left;">
                {{ $record['ds_mkc_source_feed'] }}
            </td>
        </tr>

        <tr>
            <td>
                Donor Segment
            </td>
            <td style="text-align: left;">
                {{ $record['DonorSegment'] }}
            </td>

            <td>
                Event Segment
            </td>
            <td style="text-align: left;">
                {{ $record['EventSegment'] }}
            </td>
            <td>Lifecyle Segment</td>
            <td style="text-align: left;">
                {{ $record['LifecycleSegment'] }}
            </td>
        </tr>

        <tr>
            <td style="color: #ffffff;
    font-weight: bold;"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td style="color: #357EC7;
    font-weight: 500;">Notes</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td>Additional Information</td>
            <td>
                {{ $record['Notes'] }}
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>


        </tbody>
    </table>

@elseif($section == 'summary')

    <table>
        <thead>
            <tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
        </thead>
        <tbody>
            <tr>
                <td style="color:#357EC7; font-weight:500;">Total Spend Amount</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Lifetime</td>
                <td>
                    {!! $record['Lifetime_SpendAmount'] !!}
                </td>
                <td>Last 3 years</td>
                <td>
                    {!! $record['Last36Mth_SpendAmount'] !!}
                </td>
                <td>Last 2 years</td>
                <td>
                    {!! $record['Last24Mth_SpendAmount'] !!}
                </td>
                <td>Last 1 year</td>
                <td>
                    {!! $record['Last12Mth_SpendAmount'] !!}
                </td>
                <td>Last 6 months</td>
                <td>
                    {!! $record['Last06Mth_SpendAmount'] !!}
                </td>
            </tr>
            <tr>
                <td>Last 24 - 36 months</td>
                <td>
                    {!! $record['P06_Last24_36Mth_SpendAmount'] !!}
                </td>
                <td>Last 18 - 24 months</td>
                <td>
                    {!! $record['P06_Last18_24Mth_SpendAmount'] !!}
                </td>
                <td>Last 12 - 18 months</td>
                <td>
                    {!! $record['P06_Last12_18Mth_SpendAmount'] !!}
                </td>
                <td>Last 6 - 12 months</td>
                <td>
                    {!! $record['P06_Last06_12Mth_SpendAmount'] !!}
                </td>
                <td>Last 3-6 months</td>
                <td>
                    {!! $record['P06_Last03_06Mth_SpendAmount'] !!}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td style="color:#357EC7; font-weight:500;">Gift Amount</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Lifetime</td>
                <td>
                    {!! $record['Lifetime_GiftAmount'] !!}
                </td>
                <td>Last 3 years</td>
                <td>
                    {!! $record['Last36Mth_GiftAmount'] !!}
                </td>
                <td>Last 2 years</td>
                <td>
                    {!! $record['Last24Mth_GiftAmount'] !!}
                </td>
                <td>Last 1 years</td>
                <td>
                    {!! $record['Last12Mth_GiftAmount'] !!}
                </td>
                <td>Last 6 months</td>
                <td>
                    {!! $record['Last06Mth_GiftAmount'] !!}
                </td>
            </tr>
            <tr>
                <td>Last 24 - 36 months</td>
                <td>
                    {!! $record['P06_Last24_36Mth_GiftAmount'] !!}
                </td>
                <td>Last 18 - 24 months</td>
                <td>
                    {!! $record['P06_Last18_24Mth_GiftAmount'] !!}
                </td>
                <td>Last 12 - 18 months</td>
                <td>
                    {!! $record['P06_Last12_18Mth_GiftAmount'] !!}
                </td>
                <td>Last 6 - 12 months</td>
                <td>
                    {!! $record['P06_Last06_12Mth_GiftAmount'] !!}
                </td>
                <td>Last 3-6 months</td>
                <td>
                    {!! $record['P06_Last03_06Mth_GiftAmount'] !!}
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td style="color:#357EC7; font-weight:500;">Membership Amount</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Lifetime</td>
                <td>
                    {!! $record['Lifetime_MembershipAmount'] !!}
                </td>
                <td>Last 3 years</td>
                <td>
                    {!! $record['Last36Mth_MembershipAmount'] !!}
                </td>
                <td>Last 2 years</td>
                <td>
                    {!! $record['Last24Mth_MembershipAmount'] !!}
                </td>
                <td>Last 1 year</td>
                <td>
                    {!! $record['Last12Mth_MembershipAmount'] !!}
                </td>
                <td>Last 6 months</td>
                <td>
                    {!! $record['Last06Mth_MembershipAmount'] !!}
                </td>
            </tr>
            <tr>
                <td>Last 24 - 36 months</td>
                <td>
                    {!! $record['P06_Last24_36Mth_MembershipAmount'] !!}
                </td>
                <td>Last 18 - 24 months</td>
                <td>
                    {!! $record['P06_Last18_24Mth_MembershipAmount'] !!}
                </td>
                <td>Last 12 - 18 months</td>
                <td>
                    {!! $record['P06_Last12_18Mth_MembershipAmount'] !!}
                </td>
                <td>Last 6 - 12 months</td>
                <td>
                    {!! $record['P06_Last06_12Mth_MembershipAmount'] !!}
                </td>
                <td>Last 3-6 months</td>
                <td>
                    {!! $record['P06_Last03_06Mth_MembershipAmount'] !!}
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td style="color:#357EC7; font-weight:500;">Event Amount</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Lifetime</td>
                <td>
                    {!! $record['Lifetime_EventAmount'] !!}
                </td>
                <td>Last 3 years</td>
                <td>
                    {!! $record['Last36Mth_EventAmount'] !!}
                </td>
                <td>Last 2 years</td>
                <td>
                    {!! $record['Last24Mth_EventAmount'] !!}
                </td>
                <td>Last 1 year</td>
                <td>
                    {!! $record['Last12Mth_EventAmount'] !!}
                </td>
                <td>Last 6 months</td>
                <td>
                    {!! $record['Last06Mth_EventAmount'] !!}
                </td>
            </tr>
            <tr>
                <td>Last 24 - 36 months</td>
                <td>
                    {!! $record['P06_Last24_36Mth_EventAmount'] !!}
                </td>
                <td>Last 18 - 24 months</td>
                <td>
                    {!! $record['P06_Last18_24Mth_EventAmount'] !!}
                </td>
                <td>Last 12 - 18 months</td>
                <td>
                    {!! $record['P06_Last12_18Mth_EventAmount'] !!}
                </td>
                <td>Last 6 - 12 months</td>
                <td>
                    {!! $record['P06_Last06_12Mth_EventAmount'] !!}
                </td>
                <td>Last 3-6 months</td>
                <td>
                    {!! $record['P06_Last03_06Mth_EventAmount'] !!}
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td style="color:#357EC7; font-weight:500;">Events Attended</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            <tr>
                <td>Lifetime</td>
                <td>
                    {!! $record['Lifetime_EventsAttended'] !!}
                </td>
                <td>Last 3 years</td>
                <td>
                    {!! $record['Last36Mth_EventsAttended'] !!}
                </td>
                <td>Last 2 years</td>
                <td>
                    {!! $record['Last24Mth_EventsAttended'] !!}
                </td>
                <td>Last 1 year</td>
                <td>
                    {!! $record['Last12Mth_EventsAttended'] !!}
                </td>
                <td>Last 6 months</td>
                <td>
                    {!! $record['Last06Mth_EventsAttended'] !!}
                </td>
            </tr>
            <tr>
                <td>Last 24 - 36 months</td>
                <td>
                    {!! $record['P06_Last24_36Mth_EventsAttended'] !!}
                </td>
                <td>Last 18 - 24 months</td>
                <td>
                    {!! $record['P06_Last18_24Mth_EventsAttended'] !!}
                </td>
                <td>Last 12 - 18 months</td>
                <td>
                    {!! $record['P06_Last12_18Mth_EventsAttended'] !!}
                </td>
                <td>Last 6 - 12 months</td>
                <td>
                    {!! $record['P06_Last06_12Mth_EventsAttended'] !!}
                </td>
                <td>Last 3-6 months</td>
                <td>
                    {!! $record['P06_Last03_06Mth_EventsAttended'] !!}
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td colspan="10" style="color:#357EC7; font-weight:500;">Tenure and Recency</td>
            </tr>
            <tr>
                <td>Days Since First Visit</td>
                <td>
                    {!! $record['DaysSinceFirstVisit'] !!}
                </td>
                <td>Days Since Last Visit</td>
                <td>
                    {!! $record['DaysSinceLastVisit'] !!}
                </td>
                <td>Years since First Visit</td>
                <td>
                    {!! $record['YearsSinceFirstVisit'] !!}
                </td>
                <td>Years since Last Visit</td>
                <td>
                    {!! $record['YearsSinceLastVisit'] !!}
                </td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

@endif