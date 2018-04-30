<?php
session_start();

unset($_SESSION['csrf_token']);
$_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));

$verified = false;
if (isset($_GET['hash']) && strlen($_GET['hash']) === 32) {
    require_once(".login-info");
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
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Healthy Gaming</title>
        <meta name="description" content="Healthygaming is a social enterprise project that aims to help gamers find a good balance between a healthy lifestyle and gaming."/>

    </head>
    <body>
        <?php if ($verified) : ?>
        <div class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Your Email is verified</strong>
        </div>
        <?php endif ?>
        <nav class="navbar navbar-dark navbar-expand-lg fixed-top">
            <a class="navbar-brand background-img logo" href="#" title="Healthy Gaming"></a>
            <button class="navbar-toggler" type="button" 
            data-toggle="collapse" data-target="#navbarToggler" 
            aria-controls="navbarTogglerDemo" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link background-img controller" href="#signup" title="Sign Up Tab"></a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="main">
            <!-- <div class="background-img top"> -->
                <div class="section project" data-anchor="project">
                    <h2>About the Project</h2>
                    <p>Healthygaming is a social enterprise project that aims to help gamers find a good balance between a healthy lifestyle and gaming. The project will provide a platform to bring gamers together as a community, and various features will be introduced to motivate users and provide the knowledge needed in order to successfully achieve individual set goals.</p>
                </div>
                <div class="section founder" data-anchor="founder">
                    <div class="background-img founder-img" title="Founders of Healthygaming Christoffer Johansson and Troy Liu"></div>
                    <div class="founder-description">
                        <h2>About the Founders</h2>
                        <h4>Christoffer Johansson</h4>
                        <p>Christoffer Johansson is a social entrepreneur and traveller with a passion for gaming. After a family tragedy in 2010, Christoffer fell into depression and used video games as a temporary escape from reality. After a long fight he pulled himself out in 2014, and as of September 2015, Christoffer holds the most #1 leaderboard scores in a rhythm game called osu!.</p>
                        <p>"What motivates me to start this project is the possibility of making a difference for gamers from all over the world. After dealing with an addiction to video games of my own, I believe I have the capability of using my past experience to the project's advantage, since this project is about helping others succeed in the same battle I once fought and won."</p>
                        <h4>Troy Liu</h4>
                        <p>Troy Liu is a graphical designer by day and a gamer by night. Through a co-operation with the Government of Canada and volunteer work at the Isa Mundo Foundation, Troy has been able to contribute to those less fortunate and see firsthand the difference he can make within marginalized communities.</p>
                        <p>"I'm a simple man. I see a good opportunity, I take it. I have had my own share of gaming issues in the past and I believe Healthygaming has the ability to change lives for the better."</p>
                    </div>
                 </div>
            <!-- </div>
            <div class="background-img bottom"> -->
                <div class="section signup" data-anchor="signup" onsubmit="return signup()">
                    <h2>Signup</h2>
                    <p>If you're interested in joining, please tell us a little bit about yourself and register your interest below.</p>
                    <form class="signup-form">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="birth_year" required>
                                    <option value="">Birth Year</option>
                                    <?php foreach (range(1900, 2013) as $year):?>
                                        <option value="<?= $year ?>"><?= $year ?></option>
                                    <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-control" id="gender" required>
                                            <option value="">Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="others">Others</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" id="gender_others" placeholder="Other">
                                 </div>                                
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <select class="form-control" id="country" required>
                                            <option value="">Country</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="United States">United States</option>
                                            <option value="Germany">Germany</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-control" id="city" required>
                                            <option value="">City</option>
                                            <option value="Vancouver">Vancouver</option>
                                            <option value="Stockholm">Stockholm</option>
                                            <option value="New York">New York</option>
                                            <option value="Berlin">Berlin</option>
                                    </select>
                                </div>                                
                            </div>
                        </div>                      
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" placeholder="Email" required>
                        </div>
                        <input name="csrf_token" id="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" type="hidden">
                        <div class="submit-container">
                            <button type="submit" class="background-img signup_btn" title="Sign Up Button"></button>
                            <p class="error-msg"></p>
                        </div>
                    </form>
                    <p>By registering your interest, you agree to be notified when our Crowdfunding campaign is released and when account registration becomes available.</p>
                    <p>After verifying your email, you will be eligible for a 14-day free trial of premium membership upon account registration.</p>
                    <p>*This is not your username on our platform, it is how you wish to be referred to when we contact you.</p>
                </div>
                <div class="section faq" data-anchor="faq">
                    <h2>FAQ</h2>
                    <div class="question-container">
                        <p class="question">Q: When will this project be launched?</p>
                        <p class="anwser">
                            A: Healthygaming is projected to launch in late 2018.
                        </p>
                    </div>
                    <div class="question-container">
                        <p class="question">Q: Why should I register now if the project doesn't launch until later this year?</p>
                        <p class="answer">
                            A: Your provided information is important for us to tailor-make the ultimate platform, and to find the right partners for our users before the platform is launched.
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
                    <div class="bottom-info">
                        <div class="contact">
                            contact@healthygaming.info
                        </div>
                        <div class="name">Healthygaming<sup>TM</sup></div>
                        <div class="info">
                            <div>Registered in Sweden.</div>
                            <div>Registration No. 2018/02709.</div>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>
        <div class="joystick-container">
            <div class="background-img idle_1" title="Joystick Idle"></div>
            <div class="background-img idle_2" title="Joystick Idle"></div>
            <div class="background-img up_1" title="Joystick Up"></div>
            <div class="background-img up_2" title="Joystick Up"></div>
            <div class="background-img down_1" title="Joystick Down"></div>
            <div class="background-img down_2" title="Joystick Down"></div>
            <div class="half top"></div>
            <div class="half bottom"></div>
        </div>
        <noscript id="deferred-styles">
           <link href="css/app.min.css" rel="stylesheet">
        </noscript>
        <script>
        var loadDeferredStyles = function() {
            var addStylesNode = document.getElementById("deferred-styles");
            var replacement = document.createElement("div");
            replacement.innerHTML = addStylesNode.textContent;
            document.body.appendChild(replacement)
            addStylesNode.parentElement.removeChild(addStylesNode);
        };
        var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
            window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
        else window.addEventListener('load', loadDeferredStyles);
        </script>
        <script defer src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.9.7/jquery.fullpage.min.js"></script>
        <script defer src="js/app.min.js"></script>
    </body>
</html>