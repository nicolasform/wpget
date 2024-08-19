<?php

// This script is distributed under GNU GPL 3.0: https://www.gnu.org/licenses/gpl-3.0.en.html

function download_and_unzip_wordpress($lang_code, $local_path) {
	
    // Construct the download URL based on the language code
    $url = "https://en.wordpress.org/latest-{$lang_code}.zip";
    
    // Determine the filename to save the ZIP file as
    $zip_file = $local_path . DIRECTORY_SEPARATOR . "install.zip";
    
    // Display download message
    echo "<p>Downloading WordPress from <a href=\"$url\" target=\"_blank\">$url</a>...</p>";

    // Download the file
    $file_content = file_get_contents($url);

    if ($file_content === false) {
        echo "<p style='color: red;'>Failed to download WordPress. Please check the language code and URL.</p>";
        return;
    }

    // Save the downloaded content to a file
    file_put_contents($zip_file, $file_content);
    echo "<p>Downloaded WordPress to <code>$zip_file</code>.</p>";

    // Create the directory if it doesn't exist
    if (!is_dir($local_path)) {
        mkdir($local_path, 0755, true);
    }

    // Unzip the file
    echo "<p>Unzipping WordPress...</p>";
    $zip = new ZipArchive;
    if ($zip->open($zip_file) === TRUE) {
        $zip->extractTo($local_path);
        $zip->close();
        echo "<p>WordPress unzipped to <code>$local_path</code>.</p>";
    } else {
        echo "<p style='color: red;'>Failed to unzip the WordPress package.</p>";
        return;
    }

    // Delete the ZIP file after extraction
    echo "<p>Cleaning up temporary files...</p>";
    unlink($zip_file);

    // Handle the case where the unzipped content is in a single directory
    $extracted_files = scandir($local_path);
    $directories = array_filter($extracted_files, function ($item) use ($local_path) {
        return is_dir($local_path . DIRECTORY_SEPARATOR . $item) && $item !== '.' && $item !== '..';
    });

    if (count($directories) === 1) {
        $single_dir = $local_path . DIRECTORY_SEPARATOR . reset($directories);
        echo "<p>Detected single directory: <code>$single_dir</code></p>";

        // Move all contents to the parent directory
        $files = scandir($single_dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                rename($single_dir . DIRECTORY_SEPARATOR . $file, $local_path . DIRECTORY_SEPARATOR . $file);
            }
        }

        // Remove the empty directory
        rmdir($single_dir);
        echo "<p>Moved files and deleted the empty directory.</p>";
    }

    // Delete this script
    echo "<p>Deleting the script itself...</p>";
    unlink(__FILE__);
    echo "<p style='color: green;'>Wordpress has been downloaded</p><p><a href=\".\">Go to installation</a></p>";
}

// Check if required GET parameters are set
if (isset($_GET['lang'])) {
    $language_code = $_GET['lang'];
    $destination_path = ".";
	if(isset($_GET['path']) && $_GET['path'] != null && $_GET['path'] != "") {
		$destination_path = $_GET['path'];
	}
    download_and_unzip_wordpress($language_code, $destination_path);
} else {
    // Display the form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>WordPress Installation</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                padding: 0;
            }
            h1 {
                color: #333;
            }
            form {
                margin-top: 20px;
            }
            label {
                display: block;
                margin-bottom: 5px;
            }
            input, select {
                margin-bottom: 10px;
                padding: 8px;
                width: 100%;
                max-width: 300px;
            }
            input[type="submit"] {
                background-color: #0073aa;
                color: #fff;
                border: none;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #005177;
            }
            .error {
                color: red;
            }
        </style>
    </head>
    <body>
        <h1>WordPress Installation</h1>
        <form method="GET" action="">
            <label for="lang">Language:</label>
            <select id="lang" name="lang" required>

<!-- From https://make.wordpress.org/polyglots/teams/ -->
<option>Select your language</option>
<option value='af'>Afrikaans - Afrikaans</option>
<option value='sq'>Albanian - Shqip</option>
<option value='arq'>Algerian Arabic - الدارجة الجزايرية</option>
<option value='am'>Amharic - አማርኛ</option>
<option value='ar'>Arabic - العربية</option>
<option value='arg'>Aragonese - Aragonés</option>
<option value='hy'>Armenian - Հայերեն</option>
<option value='frp'>Arpitan - Arpitan</option>
<option value='as'>Assamese - অসমীয়া</option>
<option value='ast'>Asturian - Asturianu</option>
<option value='az'>Azerbaijani - Azərbaycan dili</option>
<option value='az_TR'>Azerbaijani (Turkey) - Azərbaycan Türkcəsi</option>
<option value='bcc'>Balochi Southern - بلوچی مکرانی</option>
<option value='ba'>Bashkir - башҡорт теле</option>
<option value='eu'>Basque - Euskara</option>
<option value='bel'>Belarusian - Беларуская мова</option>
<option value='bn_BD'>Bengali (Bangladesh) - বাংলা</option>
<option value='bn_IN'>Bengali (India) - বাংলা (ভারত)</option>
<option value='bho'>Bhojpuri - भोजपुरी</option>
<option value='brx'>Bodo - बोडो‎</option>
<option value='gax'>Borana-Arsi-Guji Oromo - Afaan Oromoo</option>
<option value='bs_BA'>Bosnian - Bosanski</option>
<option value='bre'>Breton - Brezhoneg</option>
<option value='bg_BG'>Bulgarian - Български</option>
<option value='ca'>Catalan - Català</option>
<option value='bal'>Catalan (Balear) - Català (Balear)</option>
<option value='ca_valencia'>Catalan (Valencian) - Català (Valencià)</option>
<option value='ceb'>Cebuano - Cebuano</option>
<option value='zh_CN'>Chinese (China) - 简体中文</option>
<option value='zh_HK'>Chinese (Hong Kong) - 香港中文</option>
<option value='zh_SG'>Chinese (Singapore) - 中文</option>
<option value='zh_TW'>Chinese (Taiwan) - 繁體中文</option>
<option value='cor'>Cornish - Kernewek</option>
<option value='co'>Corsican - Corsu</option>
<option value='hr'>Croatian - Hrvatski</option>
<option value='cs_CZ'>Czech - Čeština</option>
<option value='da_DK'>Danish - Dansk</option>
<option value='dv'>Dhivehi - ދިވެހި</option>
<option value='nl_NL'>Dutch - Nederlands</option>
<option value='nl_BE'>Dutch (Belgium) - Nederlands (België)</option>
<option value='nl_NL_formal'>Dutch (Formal) - Nederlands (Formeel)</option>
<option value='dzo'>Dzongkha - རྫོང་ཁ</option>
<option value='art_xemoji'>Emoji - (Emoji)</option>
<option value='en_AU'>English (Australia) - English (Australia)</option>
<option value='en_CA'>English (Canada) - English (Canada)</option>
<option value='en_NZ'>English (New Zealand) - English (New Zealand)</option>
<option value='art_xpirate'>English (Pirate) - English (Pirate)</option>
<option value='en_ZA'>English (South Africa) - English (South Africa)</option>
<option value='en_GB'>English (UK) - English (UK)</option>
<option value='eo'>Esperanto - Esperanto</option>
<option value='et'>Estonian - Eesti</option>
<option value='ewe'>Ewe - Eʋegbe</option>
<option value='fo'>Faroese - Føroyskt</option>
<option value='fi'>Finnish - Suomi</option>
<option value='fon'>Fon - fɔ̀ngbè</option>
<option value='fr_BE'>French (Belgium) - Français de Belgique</option>
<option value='fr_CA'>French (Canada) - Français du Canada</option>
<option value='fr_FR'>French (France) - Français</option>
<option value='fy'>Frisian - Frysk</option>
<option value='fur'>Friulian - Friulian</option>
<option value='fuc'>Fulah - Pulaar</option>
<option value='gl_ES'>Galician - Galego</option>
<option value='ka_GE'>Georgian - ქართული</option>
<option value='de_DE'>German - Deutsch</option>
<option value='de_AT'>German (Austria) - Deutsch (Österreich)</option>
<option value='de_DE_formal'>German (Formal) - Deutsch (Sie)</option>
<option value='de_CH'>German (Switzerland) - Deutsch (Schweiz)</option>
<option value='de_CH_informal'>German (Switzerland, Informal) - Deutsch (Schweiz, Du)</option>
<option value='el'>Greek - Ελληνικά</option>
<option value='kal'>Greenlandic - Kalaallisut</option>
<option value='gu'>Gujarati - ગુજરાતી</option>
<option value='hat'>Haitian Creole - Kreyol ayisyen</option>
<option value='hau'>Hausa - Harshen Hausa</option>
<option value='haw_US'>Hawaiian - Ōlelo Hawaiʻi</option>
<option value='haz'>Hazaragi - هزاره گی</option>
<option value='he_IL'>Hebrew - עִבְרִית</option>
<option value='hi_IN'>Hindi - हिन्दी</option>
<option value='hu_HU'>Hungarian - Magyar</option>
<option value='is_IS'>Icelandic - Íslenska</option>
<option value='ido'>Ido - Ido</option>
<option value='ibo'>Igbo - Asụsụ Igbo</option>
<option value='id_ID'>Indonesian - Bahasa Indonesia</option>
<option value='ga'>Irish - Gaelige</option>
<option value='it_IT'>Italian - Italiano</option>
<option value='ja'>Japanese - 日本語</option>
<option value='jv_ID'>Javanese - Basa Jawa</option>
<option value='kab'>Kabyle - Taqbaylit</option>
<option value='kn'>Kannada - ಕನ್ನಡ</option>
<option value='kaa'>Karakalpak - Qaraqalpaq tili</option>
<option value='kk'>Kazakh - Қазақ тілі</option>
<option value='km'>Khmer - ភាសាខ្មែរ</option>
<option value='kin'>Kinyarwanda - Ikinyarwanda</option>
<option value='ko_KR'>Korean - 한국어</option>
<option value='kmr'>Kurdish (Kurmanji) - Kurdî</option>
<option value='ckb'>Kurdish (Sorani) - كوردی‎</option>
<option value='kir'>Kyrgyz - Кыргызча</option>
<option value='lo'>Lao - ພາສາລາວ</option>
<option value='lv'>Latvian - Latviešu valoda</option>
<option value='lij'>Ligurian - Lìgure</option>
<option value='li'>Limburgish - Limburgs</option>
<option value='lin'>Lingala - Ngala</option>
<option value='lt_LT'>Lithuanian - Lietuvių kalba</option>
<option value='lmo'>Lombard - Lombardo</option>
<option value='dsb'>Lower Sorbian - Dolnoserbšćina</option>
<option value='lug'>Luganda - Oluganda</option>
<option value='lb_LU'>Luxembourgish - Lëtzebuergesch</option>
<option value='mk_MK'>Macedonian - Македонски јазик</option>
<option value='mai'>Maithili - मैथिली</option>
<option value='mg_MG'>Malagasy - Malagasy</option>
<option value='ms_MY'>Malay - Bahasa Melayu</option>
<option value='ml_IN'>Malayalam - മലയാളം</option>
<option value='mlt'>Maltese - Malti</option>
<option value='mri'>Maori - Te Reo Māori</option>
<option value='mr'>Marathi - मराठी</option>
<option value='mfe'>Mauritian Creole - Kreol Morisien</option>
<option value='mn'>Mongolian - Монгол</option>
<option value='me_ME'>Montenegrin - Crnogorski jezik</option>
<option value='ary'>Moroccan Arabic - العربية المغربية</option>
<option value='my_MM'>Myanmar (Burmese) - ဗမာစာ</option>
<option value='ne_NP'>Nepali - नेपाली</option>
<option value='pcm'>Nigerian Pidgin - Nigerian Pidgin</option>
<option value='nb_NO'>Norwegian (Bokmål) - Norsk bokmål</option>
<option value='nn_NO'>Norwegian (Nynorsk) - Norsk nynorsk</option>
<option value='nqo'>N’ko - ߒߞߏ</option>
<option value='oci'>Occitan - Occitan</option>
<option value='ory'>Oriya - ଓଡ଼ିଆ</option>
<option value='os'>Ossetic - Ирон</option>
<option value='pa_IN'>Panjabi (India) - ਪੰਜਾਬੀ</option>
<option value='pap_AW'>Papiamento (Aruba) - Papiamento</option>
<option value='pap_CW'>Papiamento (Curaçao and Bonaire) - Papiamentu</option>
<option value='ps'>Pashto - پښتو</option>
<option value='fa_IR'>Persian - فارسی</option>
<option value='fa_AF'>Persian (Afghanistan) - (فارسی (افغانستان</option>
<option value='pcd'>Picard - Ch’ti</option>
<option value='pl_PL'>Polish - Polski</option>
<option value='pt_AO'>Portuguese (Angola) - Português de Angola</option>
<option value='pt_BR'>Portuguese (Brazil) - Português do Brasil</option>
<option value='pt_PT'>Portuguese (Portugal) - Português</option>
<option value='pt_PT_ao90'>Portuguese (Portugal, AO90) - Português (AO90)</option>
<option value='pa_PK'>Punjabi (Pakistan) - پنجابی</option>
<option value='rhg'>Rohingya - Ruáinga</option>
<option value='ro_RO'>Romanian - Română</option>
<option value='roh'>Romansh - Rumantsch</option>
<option value='ru_RU'>Russian - Русский</option>
<option value='sah'>Sakha - Сахалыы</option>
<option value='sa_IN'>Sanskrit - भारतम्</option>
<option value='skr'>Saraiki - سرائیکی</option>
<option value='srd'>Sardinian - Sardu</option>
<option value='gd'>Scottish Gaelic - Gàidhlig</option>
<option value='sr_RS'>Serbian - Српски језик</option>
<option value='sr_RS_latin'>Serbian (Latin) - Srpski jezik</option>
<option value='sna'>Shona - ChiShona</option>
<option value='sq_XK'>Shqip (Kosovo) - Për Kosovën Shqip</option>
<option value='scn'>Sicilian - Sicilianu</option>
<option value='szl'>Silesian - Ślōnskŏ gŏdka</option>
<option value='snd'>Sindhi - سنڌي</option>
<option value='si_LK'>Sinhala - සිංහල</option>
<option value='sk_SK'>Slovak - Slovenčina</option>
<option value='sl_SI'>Slovenian - Slovenščina</option>
<option value='so_SO'>Somali - Afsoomaali</option>
<option value='azb'>South Azerbaijani - گؤنئی آذربایجان</option>
<option value='es_AR'>Spanish (Argentina) - Español de Argentina</option>
<option value='es_CL'>Spanish (Chile) - Español de Chile</option>
<option value='es_CO'>Spanish (Colombia) - Español de Colombia</option>
<option value='es_CR'>Spanish (Costa Rica) - Español de Costa Rica</option>
<option value='es_DO'>Spanish (Dominican Republic) - Español de República Dominicana</option>
<option value='es_EC'>Spanish (Ecuador) - Español de Ecuador</option>
<option value='es_GT'>Spanish (Guatemala) - Español de Guatemala</option>
<option value='es_HN'>Spanish (Honduras) - Español de Honduras</option>
<option value='es_MX'>Spanish (Mexico) - Español de México</option>
<option value='es_PE'>Spanish (Peru) - Español de Perú</option>
<option value='es_PR'>Spanish (Puerto Rico) - Español de Puerto Rico</option>
<option value='es_ES'>Spanish (Spain) - Español</option>
<option value='es_UY'>Spanish (Uruguay) - Español de Uruguay</option>
<option value='es_VE'>Spanish (Venezuela) - Español de Venezuela</option>
<option value='su_ID'>Sundanese - Basa Sunda</option>
<option value='sw'>Swahili - Kiswahili</option>
<option value='ssw'>Swati - SiSwati</option>
<option value='sv_SE'>Swedish - Svenska</option>
<option value='syr'>Syriac - Syriac</option>
<option value='tl'>Tagalog - Tagalog</option>
<option value='tah'>Tahitian - Reo Tahiti</option>
<option value='tg'>Tajik - Тоҷикӣ</option>
<option value='zgh'>Tamazight - ⵜⴰⵎⴰⵣⵉⵖⵜ</option>
<option value='tzm'>Tamazight (Central Atlas) - ⵜⴰⵎⴰⵣⵉⵖⵜ</option>
<option value='ta_IN'>Tamil - தமிழ்</option>
<option value='ta_LK'>Tamil (Sri Lanka) - தமிழ்</option>
<option value='tt_RU'>Tatar - Татар теле</option>
<option value='te'>Telugu - తెలుగు</option>
<option value='th'>Thai - ไทย</option>
<option value='bo'>Tibetan - བོད་ཡིག</option>
<option value='tir'>Tigrinya - ትግርኛ</option>
<option value='tr_TR'>Turkish - Türkçe</option>
<option value='tuk'>Turkmen - Türkmençe</option>
<option value='twd'>Tweants - Twents</option>
<option value='ug_CN'>Uighur - ئۇيغۇرچە</option>
<option value='uk'>Ukrainian - Українська</option>
<option value='hsb'>Upper Sorbian - Hornjoserbšćina</option>
<option value='ur'>Urdu - اردو</option>
<option value='uz_UZ'>Uzbek - O‘zbekcha</option>
<option value='vec'>Venetian - Vèneto</option>
<option value='vi'>Vietnamese - Tiếng Việt</option>
<option value='cy'>Welsh - Cymraeg</option>
<option value='bgn'>Western Balochi - بلوچی‎</option>
<option value='wol'>Wolof - Wolof</option>
<option value='xho'>Xhosa - isiXhosa</option>
<option value='yor'>Yoruba - Yorùbá</option>
<option value='zul'>Zulu - isiZulu</option>

            </select>
            <br>
            <label for="path">Installation Path (leave empty for current directory):</label>
            <input type="text" id="path" name="path" placeholder="/path/to/your/destination">
            <br>
            <input type="submit" onclick='this.setAttribute("disabled", "disabled"); this.setAttribute("style", "background-color: grey");  this.value="Installing (may take a while)..."; this.form.submit();' value="Install WordPress">
        </form>
    </body>
    </html>
    <?php
}
?>
