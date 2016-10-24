<?php

function lscs_shortcode_banner($atts)
{

    //Set up our personalization variables
    $Geo = 'USA';
    $Persona = 'Adult';

    //Debug variable
    $Debug = false;
    //TODO change to false before deployment

    //Grab any URL query string parameters so we can use them later

    if (isset($_REQUEST['geo'])) {
        switch (strtolower($_REQUEST['geo'])) {
            case 'west':
                $Geo = 'West';
                break;
            case 'east':
                $Geo = 'East';
                break;
            default:
                $Geo = 'USA';
        }
    }

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
    switch ($Geo) {
        case 'West':
            switch ($Persona) {
                case 'Student':
                    $TargetBanner = 'techgeneration.xml';
                    break;
                case 'Senior':
                    $TargetBanner = 'LikeNoOther.xml';
                    break;
                default:
                    $TargetBanner = 'hometownbank.xml';
                    break;
            }
            break;
        case 'East':
            switch ($Persona) {
                case 'Student':
                    $TargetBanner = 'BankThatMovesQuickly.xml';
                    break;
                case 'Senior':
                    $TargetBanner = 'BankThatUnderstands.xml';
                    break;
                default:
                    $TargetBanner = 'NewPlayerInTown.xml';
                    break;
            }
            break;
        default:
            $TargetBanner = 'BankingForInternet.xml';

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
                . '/document/path/templatedata/LiveSite/Banner/data/' . $TargetBanner
                . '?' . $ProjectName;
            break;

        case 'ew2016':
            //This is the base LSCS demo server URL - all REST to this server calls begin with this in the string
            $LSCS_Server = 'http://ew2016.teamsite8demo.com:1876/lscs/v1';
            //The fully-qualified project name
            $ProjectName = 'project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank';

            $RequestURL = $LSCS_Server
                . '/document/path/templatedata/LiveSite/Banner/data/' . $TargetBanner
                . '?' . $ProjectName;
            break;

        default;
            $Output = 'Error! No known server selected';
            return $Output;
    }

    $BannerXML = simplexml_load_file($RequestURL);
    $BannerCaption = $BannerXML->banner_caption;
    $BannerImageRelPath = $BannerXML->banner_image;

    $Output .= '<h2>' . $BannerCaption . '</h2>';
    $ImgSrc = $LSCS_Server . '/document/path' . $BannerImageRelPath . '?' . $ProjectName;
    $Output .= '<img src="' . $ImgSrc . '">';


    //DEBUG output
    if ($Debug == true) {
        $Output .= '<p>Debug Banner Data</p>';
        $Output .= '<ul>';
        $Output .= '<li>' . '$Geo = ' . $Geo . '</li>';
        $Output .= '<li>' . '$Persona = ' . $Persona . '</li>';
        $Output .= '<li>' . 'Banner Doc = ' . $TargetBanner . '</li>';
        $Output .= '<li>' . 'banner_caption = ' . $BannerCaption . '</li>';
        $Output .= '<li>' . 'banner_image = ' . $BannerImageRelPath . '</li>';
        $Output .= '</ul>';
    }

    return $Output;
}


/*
    LSCS system status
    http://ew2016.teamsite8demo.com:1876/lscs/v1/admin/status
    http://pocteamsite02.eastus.cloudapp.azure.com:1876/lscs/v1/admin/status

    Access LSCS debug panel here
    http://ew2016.teamsite8demo.com:1876/lscs/admin/debugpanel/
    http://pocteamsite02.eastus.cloudapp.azure.com:1876/lscs/admin/debugpanel/

    Get list of projects here
    http://ew2016.teamsite8demo.com:1876/lscs/v1/projects

    Sample call to pull the Student_Loans.xml file from the AuraBank project
    http://ew2016.teamsite8demo.com:1876/lscs/v1/document/path/templatedata/LiveSite/Content/data/Promo/Student_Loans.xml?project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank

    Sample call to pull the Student_Loans.xml METADATA from the AuraBank project (note the $ at the end of the file name
    http://ew2016.teamsite8demo.com:1876/lscs/v1/document/path/templatedata/LiveSite/Content/data/Promo/Student_Loans.xml$?format=json&project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank
*/






