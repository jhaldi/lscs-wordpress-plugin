<?php

function lscs_shortcode_promo($atts)
{

    //Set up our personalization variables
    $Persona = 'Adult';

    //Debug variable
    $Debug = false;
    //TODO change to false before deployment

    //Grab any URL query string parameters

    if (isset($_REQUEST['persona'])) {
        switch (strtolower($_REQUEST['persona'])) {
            case 'senior':
                $Persona = 'Senior';
                break;
            case 'student':
                $Persona = 'Student';
                break;
            default:
                $Persona = 'Adult';
        }
    }

    if (isset($_REQUEST['debug'])) {
        if (strtolower($_REQUEST['debug'] == 'true')) {
            $Debug = true;
        }
    }

    //Empty placeholder to store the snippet that we pass back to WordPress to insert wherever the shortcode was called
    $Output = '';

    //NOTE:  This is a hard-coded rule for demo purposes.  Production instance could leverage
    //       the LCSC rules engine to determine content dynamically.  Definitely not best practice here.

    switch ($Persona) {
        case 'Student':
            $TargetPromo = 'banktoschoolspecial.xml';
            break;
        case 'Senior':
            $TargetPromo = 'highinterestsavingsaccounts.xml';
            break;
        default:
            $TargetPromo = 'nofeeira.xml';
            break;
    }

    $targetMachine = 'azure';
    //$targetMachine = 'ew2016';

    $LSCS_Server = '';
    $ProjectName = '';
    $RequestURL = '';

    switch ($targetMachine) {

        case 'azure':
            //This is the base LSCS demo server URL - all REST to this server calls begin with this in the string
            $LSCS_Server = 'http://pocteamsite02.eastus.cloudapp.azure.com:1876/lscs/v1';
            //The fully-qualified project name
            $ProjectName = 'project=//pocteamsite02/default/main/AuraBank';

            $RequestURL = $LSCS_Server
                . '/document/path/templatedata/LiveSite/Promo/data/' . $TargetPromo
                . '?' . $ProjectName;
            break;

        case 'ew2016':
            //This is the base LSCS demo server URL - all REST to this server calls begin with this in the string
            $LSCS_Server = 'http://ew2016.teamsite8demo.com:1876/lscs/v1';
            //The fully-qualified project name
            $ProjectName = 'project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank';

            $RequestURL = $LSCS_Server
                . '/document/path/templatedata/LiveSite/Promo/data/' . $TargetPromo
                . '?' . $ProjectName;
            break;

        default;
            $Output = 'Error! No known server selected';
            return $Output;
    }

    $PromoXML = simplexml_load_file($RequestURL);
    $PromoTitle = $PromoXML->promo_title;
    $PromoDescription = $PromoXML->promo_description;

    $Output .= '<h2>' . $PromoTitle . '</h2>';
    $Output .= '<p>' . $PromoDescription . '</p>';

    //DEBUG output
    if ($Debug == true) {
        $Output .= '<p>Debug Promo Data</p>';
        $Output .= '<ul>';
        $Output .= '<li>' . '$Persona = ' . $Persona . '</li>';
        $Output .= '<li>' . 'promo_title = ' . $PromoTitle . '</li>';
        $Output .= '<li>' . 'promo_description = ' . $PromoDescription . '</li>';
        $Output .= '</ul>';
    }

    return $Output;


    /*

       //Preview LSCS system status here
       //http://ew2016.teamsite8demo.com:1876/lscs/v1/admin/status

       $StatusURL = 'http://ew2016.teamsite8demo.com:1876/lscs/v1/admin/status';

     //Preview Student Loan XML here
       //http://ew2016.teamsite8demo.com:1876/lscs-static/37a/ce7/37ace78dd16857c3ba5e9416b50419ed/content/templatedata/LiveSite/Content/data/Promo/Student_Loans.xml
       //

   */


}


