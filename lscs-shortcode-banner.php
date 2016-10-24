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
    $curlMeta = curl_init();
    curl_setopt($curlMeta, CURLOPT_URL, $BaseURL . "/document/path/templatedata/LiveSite/Content/data/Promo/Student_Loans.xml$?format=json&" . $ProjectName);
    curl_setopt($curlMeta, CURLOPT_RETURNTRANSFER, 1);
    $resultMeta = curl_exec($curlMeta);
    curl_close($curlMeta);

    //$jsonResult = $result;
    $jsonResultMeta = json_decode($resultMeta, true);

        //http://ew2016.teamsite8demo.com:1876/lscs/v1/document/path/templatedata/LiveSite/Promo/data/nofeeira.xml?project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank

    $Output .= '<h3>MetaData (JSON)</h3>';
    //var_dump( $jsonResultMeta['results']['assets'][0]['metadata']);

    $Output .= '<p>' . 'Count = ' . count($jsonResultMeta['results']['assets'][0]['metadata']) . '</p>';
    $Output .= '<ul>';
    $Output .= '<li>' . $jsonResultMeta['results']['assets'][0]['metadata']['/Content/Title'] . '</li>';
    $Output .= '<li>' . $jsonResultMeta['results']['assets'][0]['metadata']['/Content/Summary'] . '</li>';
    $Output .= '<li>' . $jsonResultMeta['results']['assets'][0]['metadata']['/Content/Images/Image/RelativePath'] . '</li>';
    $Output .= '</ul>';
    $ImgSrc = $BaseURL . '/document/path' . $jsonResultMeta['results']['assets'][0]['metadata']['/Content/Images/Image/RelativePath'] . '?' . $ProjectName;
    $Output .= '<img src="' . $ImgSrc . '">';
    $Output .= '<li>' . $ImgSrc . '"</li>';
 */

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

/*
    $curlProjects = curl_init();
    curl_setopt($curlProjects, CURLOPT_URL, $BaseURL . "/projects?format=json");
    curl_setopt($curlProjects, CURLOPT_RETURNTRANSFER, 1);
    $resultProjects = curl_exec($curlProjects);
    curl_close($curlProjects);

    $jsonResultProjects = json_decode($resultProjects, true);

    $Output .= '<h3>Project Names (JSON)</h3>';
        $Output .= '<ul>';
    for ($i = 0; $i < count($jsonResultProjects['projects']); ++$i) {
        $Output .= '<li>' . $jsonResultProjects['projects'][$i]['name'] . '</li>';
    }
    $Output .= '</ul>';

http://ew2016.teamsite8demo.com:1876/lscs/v1/document/path/templatedata/LiveSite/Promo/data/highinterestsavingsaccounts.xml?project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank

http://ew2016.teamsite8demo.com:1876/lscs/v1/document/path/templatedata/LiveSite/Promo/data/highinterestsavingsaccounts.xml*?project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank&format=json

http://ew2016.teamsite8demo.com:1876/lscs/v1/document/path/templatedata/LiveSite/Banner/data/BankThatMovesQuickly.xml?project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank

http://ew2016.teamsite8demo.com:1876/lscs/v1/document/path/templatedata/LiveSite/Banner/data/BankThatMovesQuickly.xml$?project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank

http://ew2016.teamsite8demo.com:1876/lscs/v1/document/path/templatedata/LiveSite/Banner/data/BankThatMovesQuickly.xml$?project=//ip-172-30-0-41.ec2.internal/default/main/AuraBank&format=json

*/





