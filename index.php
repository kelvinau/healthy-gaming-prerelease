<?php
session_start();

$VERSION = '1.2.4';

unset($_SESSION['csrf_token']);
$_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));

$verified = false;
if (isset($_GET['hash']) && strlen($_GET['hash']) === 32) {
    require_once("../../login_info/pr.php");
    $conn = new mysqli($SERVER, $USER, $PW, $DB);

    if ($conn->connect_errno) {
        echo "Failed to connect to the database";
    }
    else {
        $TABLE = 'registration';
        $hash = $_GET['hash'];

        $stmt = $conn->prepare("SELECT email FROM {$TABLE} WHERE hash=? AND verified=FALSE");
        $stmt->bind_param("s", $hash);

        $result = $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $sql = "UPDATE {$TABLE} SET verified = TRUE WHERE hash='{$hash}'";
            $result = $conn->query($sql);
            $verified = true;
        }
    }
}

$COUNTRY_LIST = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua andBarbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas"
,"Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia andHerzegovina","Botswana","Brazil","British Virgin Islands"
,"Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica"
,"Cote D Ivoire","Croatia","Cruise Ship","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea"
,"Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana"
,"Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India"
,"Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kuwait","Kyrgyz Republic","Laos","Latvia"
,"Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Mauritania"
,"Mauritius","Mexico","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Namibia","Nepal","Netherlands","Netherlands Antilles","New Caledonia"
,"New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal"
,"Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre andMiquelon","Samoa","San Marino","Satellite","Saudi Arabia","Senegal","Serbia","Seychelles"
,"Sierra Leone","Singapore","Slovakia","Slovenia","South Africa","South Korea","Spain","Sri Lanka","St Kitts andNevis","St Lucia","St Vincent","St. Lucia","Sudan"
,"Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad andTobago","Tunisia"
,"Turkey","Turkmenistan","Turks and Caicos","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay","Uzbekistan","Venezuela","Vietnam","Virgin Islands (US)"
,"Yemen","Zambia","Zimbabwe"];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117998010-3"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-117998010-3');
		</script>
        <title>HealthyGaming - Making a Healthy Difference for Gamers</title>
        <meta name="description" content="HealthyGaming is a social enterprise project that aims to help gamers find a good balance between a healthy lifestyle and gaming."/>
        <meta name="keywords" content="
        Healthy Gaming, HealthyGaming, Gamer Social Enterprise, Stress from video games, Project that helps gamers, Mental problem gaming,
        addicted to gaming, Negative effect of gaming, Gamer Mental Health" />
        <link href="css/app.min.css?v=<?= $VERSION ?>" rel="stylesheet">
        <link rel="shortcut icon" href="image/favicon.ico" />
    </head>
    <body class="<?= $verified ? 'verified' : '' ?>" >
        <h1 style="display: none;">HealthyGaming</h1>
        <div class="loading"><img width="60" height="60" src="image/loading.gif"></div>
        <nav class="navbar navbar-dark navbar-expand-lg fixed-top">
            <a class="navbar-brand background-img logo" href="#" title="HealthyGaming" onclick="gotoTop()"></a>
            <button class="navbar-toggler" type="button"
            data-toggle="collapse" data-target="#navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0"></ul>
                <ul class="navbar-nav mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#project">About the Project</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#founder">About the Founders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link background-img controller" href="#signup" title="Click here to sign up"></a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="main">
            <div class="section project background-img background-1" data-anchor="project" id="section-project">
                <div class="content-wrapper">
                    <h2>About the Project</h2>
                    <p>HealthyGaming is a social enterprise project that aims to help gamers find a good balance between a healthy lifestyle and gaming. The project will provide a platform to bring gamers together as a community, and various features will be introduced to motivate users and provide the knowledge needed in order to successfully achieve individual set goals.</p>
                </div>
            </div>
            <div class="section founder background-img background-2" data-anchor="founder" id="section-founder">
                <div class="content-wrapper row">
                    <div class="col-12 col-md-4 founder-img-container">
                        <img class="founder-img" src="image/founders-Christoffer-Troy.jpg" title="founders-Christoffer-Troy">
                        <img class="founder-img" src="image/founder-Kelvin.jpg" title="founder-Kelvin">
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="founder-description">
                            <h2>About the Founders</h2>

                            <h4><b>Christoffer Johansson</b></h4>
                            <p>Christoffer Johansson is a social entrepreneur and traveller with a passion for gaming.
                            After a family tragedy in 2010, Christoffer fell into depression and used video games as a temporary escape from reality.
                            After a long fight he pulled himself out in 2014, and as of September 2015,
                            Christoffer holds the most #1 leaderboard scores in a rhythm game called osu!.</p>
                            <p><i>"What motivates me to start this project is
                            the possibility of making a difference for gamers from all over the world.
                            After dealing with an addiction to video games of my own,
                            I believe I have the capability of using my past experience to the project's advantage,
                            since this project is about helping others succeed in the same battle I once fought and won."</i></p>

                            <h4><b>Troy Liu</b></h4>
                            <p>Troy Liu is a graphical designer by day and a gamer by night.
                            Through a co-operation with the Government of Canada and volunteer work at the Isa Mundo Foundation,
                            Troy has been able to contribute to those less fortunate and see firsthand the difference he can make within marginalized communities.</p>
                            <p><i>"I'm a simple man. I see a good opportunity, I take it.
                            I have had my own share of gaming issues in the past and
                            I believe HealthyGaming has the ability to change lives for the better."</i></p>

                            <h4><b>Kelvin Au</b></h4>
                            <p>Kelvin Au is a software developer who loves technology and believes his passion and coding skills can make lives better.
                            Kelvin could game for 16 hours non-stop for consecutive days when he was young,
                            until he found another activity that was more interesting to keep spending days for
                            - writing code to realize his random ideas.</p>
                            <p><i>"I can be easily addicted to exciting video games, but none of them can be compared to the fun of building something
                            with just a computer and my determined attitude."</i></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section signup background-img background-3" data-anchor="signup" id="section-signup" onsubmit="return signup()">
                <div class="content-wrapper">
                <h2>Signup</h2>
                    <p>If you're interested in joining, please tell us a little bit about yourself and register your interest below.</p>
                    <form class="signup-form">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" placeholder="Name" required>
                            <p><small>* This is how you wish to be referred to when we contact you.</small></p>
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-control" id="birth_year" required>
                                        <option value="">Year of Birth</option>
                                        <?php foreach (range(date('Y') - 10, 1900) as $year):?>
                                            <option value="<?= $year ?>"><?= $year ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-control" id="gender" required>
                                        <option value="">Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="country" required>
                                    <option value="">Country</option>
                                    <?php foreach ($COUNTRY_LIST as $c):?>
                                        <option value="<?= $c ?>"><?= $c ?></option>
                                    <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" placeholder="Email" required>
                        </div>
                        <input name="csrf_token" id="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" type="hidden">
                        <div class="submit-container">
                            <button type="submit" class="background-img signup_btn" title="Click here to register" id="signUp-btn"></button>
                            <p class="error-msg"></p>
                        </div>
                    </form>
                    <p>By registering your interest, you agree to our
                        <a href="#" onclick="showModal('disclaimer', event)">disclaimer</a>
                    and to be notified when our Crowdfunding campaign is released and when account registration becomes available.</p>
                    <p>After verifying your email, you will be eligible for a 14-day free trial of premium membership upon account registration.</p>
                </div>
            </div>
            <div class="section faq background-img background-4" data-anchor="faq" id="section-faq">
                <div class="content-wrapper">
                    <h2>FAQ</h2>
                    <div class="question-container">
                        <p class="question">Q: When will this project be launched?</p>
                        <p class="anwser">
                            A: HealthyGaming is projected to launch in Spring 2019.
                        </p>
                    </div>
                    <div class="question-container">
                        <p class="question">Q: Why should I register now if the project doesn't launch until next year?</p>
                        <p class="answer">
                            A: Your provided information is important for us to tailor-make the ultimate platform for you and other users.
                        </p>
                    </div>
                    <div class="question-container">
                        <p class="question">Q: There have been many projects like this one, yet none seemed to really take off; What makes you think this one will?</p>
                        <p class="answer">
                            A: This is a social enterprise project by gamers, for gamers. We have a unique inside knowledge of how to deal with gaming addictions and combining that with our continuous research, innovative features and our expansive network will allow our community to grow together and help each other strive towards individual goals, regardless of what they are.
                        </p>
                    </div>
                    <div class="question-container">
                        <p class="question">Q: Premium features? Shouldn't a project like this be free for everyone?</p>
                        <p class="answer">
                            A:  All essential content and features will be accessible for free. Upgrading your membership will allow for more features and greater deals while contributing to help run and maintain the project.
                        </p>
                    </div>
                    <div class="question-container">
                        <p class="question">Q:  I'd like to contribute; Is there anything I can do?</p>
                        <p class="answer">
                                A: The project is currently in development. When it releases, opportunities will become available for you to help out in the community in various ways. At the moment, simply showing your interest by signing up and spreading the word are the best ways to assist us in pushing this project forward.
                        </p>
                    </div>
                    <div class="bottom-info" style="text-align: left;">
						<div style="margin-bottom: 15px;">CONTACT US</div>
                        <div class="contact" style="margin-bottom: 15px;">
							<div>HealthyGaming Limited</div>
							<div>20<sup>th</sup> Floor, Central Tower, 28 Queen’s Road Central</div>
							<div>Central, Hong Kong</div>
                        </div>
						<div style="margin-bottom: 15px;">Email: <a href="mailto:contact@healthygaming.info">contact@healthygaming.info</a></div>

                        <div class="name">HealthyGaming<sup>&reg;</sup>. All rights reserved.</div>

                    </div>
                </div>
            </div>
        </div>
        <div class="joystick-container">
            <div class="click-me">Click Me</div>
            <!-- <div class="background-img idle_1" title="Joystick Idle"></div> -->
            <div class="background-img idle_2" title="Joystick Idle"></div>
            <!-- <div class="background-img up_1" title="Joystick Up"></div> -->
            <div class="background-img up_2" title="Joystick Up"></div>
            <!-- <div class="background-img down_1" title="Joystick Down"></div> -->
            <div class="background-img down_2" title="Joystick Down"></div>
            <div class="half top"></div>
            <div class="half bottom"></div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b id="modalTitle"></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalBody">
                        <div class="disclaimer">
                            HealthyGaming<sup>&reg;</sup> provides the [https://healthygaming.info] website as a service to the public and website owners.<br>
                            By reading this, you acknowledge that you are only allowed to register information about yourself and give us the consent to
                            process your registration with our Social Enterprise.<br>
                            The information provided when signing up, which is stored by HealthyGaming<sup>&reg;</sup>
                            is protected according to the EU General Data Protection Regulation (2016/679) and will not be shared with any third party.<br>
                            This data will only be used for the purpose of learning more about our target audience,
                            which allows us to tailor-make the website and find partners that benefits those signing up in multiple ways.<br>
                            [Example: If 50,000 US citizens sign up between the age 18-25,
                            it would make perfect sense to use our resources looking for partners and deals that
                            those 50,000 US citizens can use in a beneficial and good way.]<br><br>
                            If you want to have your personal information transferred, deleted or want to learn more about the information
                            we have and withdraw your consent that you have given us when signing up, you have the right to request this by
                            submitting an SAR (Subject Access Request), we are obliged to handle these requests within 30 days.<br>
                            We will not delete any data that is stored during the pre-release unless an SAR (Subject Access Request) is sent to
                            <a href="mailto:contact@healthygaming.info">contact@healthygaming.info</a> where we are requested to do so.<br></br>
                            When signing up, you agree to this disclaimer and acknowledge that you have read all the information.
                            You also accept that HealthyGaming<sup>&reg;</sup> is not responsible for, and expressly disclaims all liability for damages of any kind arising out of use,
                            reference to, or reliance on any information contained within the site.
                            While the information contained within the site is encrypted and periodically updated,
                            no guarantee is given that the encrypted personal information is correct, complete, and up-to-date.
                        </div>
                        <div class="placeholder">
                            Thank you for your time and interest.<br>
                            Your Email is now verified, and your registration is complete. <br>
                            For further questions, don’t hesitate to contact us.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
        // var loadDeferredStyles = function() {
        //     var addStylesNode = document.getElementById("deferred-styles");
        //     var replacement = document.createElement("div");
        //     replacement.innerHTML = addStylesNode.textContent;
        //     document.body.appendChild(replacement)
        //     addStylesNode.parentElement.removeChild(addStylesNode);
        // };
        // var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
        //     window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        // if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
        // else window.addEventListener('load', loadDeferredStyles);
        </script>
        <!-- For development -->
        <!-- <script defer src="js/app.min.js?<?= time() ?>"></script> -->
        <script defer src="js/app.min.js?v=<?= $VERSION ?>"></script>
    </body>
</html>
