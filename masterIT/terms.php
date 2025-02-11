<?php
session_start();
define("PAGE_TITLE", "Terms & Conditions");
require "dbh/dbdata.php";
require "includes/header.php";
include_once "components/navbar.php";
include_once "components/topBar.php";
?>

<!-- TERMS AND CONDITIONS SECTION -->
<section class="main-section container-xl mx-auto mb-5">
    <!-- TopBar -->
    <?= topBar("Business Terms") ?>
    <div class="container-xl resources text-start">
        <h2><strong>Terms and Conditions</strong></h2>

        <p>Welcome to masterit.lk!</p>

        <p>These terms and conditions outline the rules and regulations for the use of Master IT's Website, located at https://masterit.lk/.</p>

        <p>By accessing this website we assume you accept these terms and conditions. Do not continue to use masterit.lk if you do not agree to take all of the terms and conditions stated on this page.</p>

        <p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: "Client", "You" and "Your" refers to you, the person log on this website and compliant to the Company's terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client's needs in respect of provision of the Company's stated services, in accordance with and subject to, prevailing law of lk. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p>

        <h3><strong>Cookies</strong></h3>

        <p>We employ the use of cookies. By accessing masterit.lk, you agreed to use cookies in agreement with the Master IT's Privacy Policy. </p>

        <p>Most interactive websites use cookies to let us retrieve the user's details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</p>

        <h3><strong>iFrames</strong></h3>

        <p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p>

        <h3><strong>Content Liability</strong></h3>

        <p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</p>

        <h3><strong>Reservation of Rights</strong></h3>

        <p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it's linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</p>

        <h3><strong>Removal of links from our website</strong></h3>

        <p>If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</p>

        <p>We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</p>

        <h3><strong>Disclaimer</strong></h3>

        <p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</p>

        <ul>
            <li>limit or exclude our or your liability for death or personal injury;</li>
            <li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
            <li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
            <li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
        </ul>

        <p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</p>

        <p>As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.</p>
    </div>
</section>
<!-- END OF SECTION TERMS AND CONDITIONS -->

<?php
require "includes/footer.php";
?>