<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>

<style>
    /*--------------------------------------------------------------
# Hero Section
--------------------------------------------------------------*/
    #hero {
        width: 100%;
        height: 100vh;
        position: relative;
        background: url("../img/hero-bg.jpg") top center;
        background-size: cover;
        position: relative;
    }

    #hero:before {
        content: "";
        position: absolute;
        bottom: 0;
        top: 0;
        left: 0;
        right: 0;
    }

    #hero .container {
        padding-top: 80px;
    }

    #hero h1 {
        margin: 0;
        font-size: 56px;
        font-weight: 700;
        line-height: 72px;
        font-family: "Poppins", sans-serif;
    }

    #hero h2 {
        margin: 10px 0 0 0;
        font-size: 22px;
    }

    #hero .btn-get-started {
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        font-size: 14px;
        letter-spacing: 0.5px;
        display: inline-block;
        padding: 14px 50px;
        border-radius: 5px;
        transition: 0.5s;
        margin-top: 30px;
        color: #fff;
        background: #2487ce;
    }

    #hero .btn-get-started:hover {
        background: #3194db;
    }

    #hero .icon-boxes {
        margin-top: 100px;
    }

    #hero .icon-box {
        padding: 50px 30px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease-in-out;
        z-index: 1;
    }

    #hero .icon-box .title {
        font-weight: bold;
    }


    #hero .icon-box .description {
        font-size: 15px;
        line-height: 28px;
        margin-bottom: 0;
    }

    #hero .icon-box .icon {
        margin-bottom: 20px;
        padding-top: 10px;
        display: inline-block;
        transition: all 0.3s ease-in-out;
        font-size: 36px;
        line-height: 1;
        color: #2487ce;
    }

    #hero .icon-box:hover {
        transform: scale(1.08);
    }

    #hero .icon-box:hover .title a {
        color: #2487ce;
    }

    @media (min-width: 1024px) {
        #hero {
            background-attachment: fixed;
        }
    }

    @media (max-height: 800px) {
        #hero {
            height: auto;
        }
    }

    @media (max-width: 992px) {
        #hero {
            height: auto;
        }

        #hero h1 {
            font-size: 28px;
            line-height: 36px;
        }

        #hero h2 {
            font-size: 18px;
            line-height: 24px;
        }
    }
</style>

<style>
    .hover {
        color: #0000;
        background:
            linear-gradient(90deg, #1095c1 50%, #000 0) var(--_p, 100%)/200% no-repeat;
        -webkit-background-clip: text;
        background-clip: text;
        transition: .4s;
    }

    .hover:hover {
        --_p: 0%;
    }

    .hover-4 {
        border: 8px solid;
        border-image: repeating-linear-gradient(135deg, #F8CA00 0 10px, #E97F02 0 20px, #BD1550 0 30px) 8;
        -webkit-mask:
            conic-gradient(from 180deg at top 8px right 8px, #0000 90deg, #000 0) var(--_i, 200%) 0 /200% var(--_i, 8px) border-box no-repeat,
            conic-gradient(at bottom 8px left 8px, #0000 90deg, #000 0) 0 var(--_i, 200%)/var(--_i, 8px) 200% border-box no-repeat,
            linear-gradient(#000 0 0) padding-box no-repeat;
        transition: .3s, -webkit-mask-position .3s .3s;
    }

    .hover-4:hover {
        --_i: 100%;
        color: yellow;
        transition: .3s, -webkit-mask-size .3s .3s;
    }
</style>


</head>



<body id="wrapper">


    <?php include "components\header.php"; ?>

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9 text-center">
                    <h1>歡迎來到排球計分系統</h1>
                    <h2>管理、編輯、分析你的球隊</h2>
                </div>
            </div>
            <div class="text-center">
                <a href=<?php
                        if (!isset($_COOKIE['teamID'])) {
                            echo 'login.php';
                        } else {
                            echo 'race.php';
                        }
                        ?> class="btn btn-primary p-3 mt-3 fs-4">立即開始</a>
            </div>

            <div class="row icon-boxes gx-5">
                <div class="hover-4 col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="200">
                    <div class="icon-box">
                        <div class="icon"><i class="bi bi-vector-pen"></i></i></div>
                        <h4 class="hover title"><a href="team.php">編輯隊伍</a></h4>
                        <p class="description">更改隊伍名稱、設定隊員的職位與球號</p>
                    </div>
                </div>

                <div class="hover-4 col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="300">
                    <div class="icon-box">
                        <div class="icon"><i class="bi bi-journal-text"></i></div>
                        <h4 class="hover title"><a href="race.php">比賽系統</a></h4>
                        <p class="description">創建、紀錄每一局比賽的得失分狀況</p>
                    </div>
                </div>

                <div class="hover-4 col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="400">
                    <div class="icon-box">
                        <div class="icon"><i class="bi bi-bar-chart-line"></i></i></div>
                        <h4 class="hover title"><a href="analyze.php">球員分析</a></h4>
                        <p class="description">分析球員的得分與防守率，找出問題，並設計練習方式!</p>
                    </div>
                </div>
                <div class="hover-4 col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="400">
                    <div class="icon-box">
                        <div class="icon"><i class="bi bi-book"></i></div>
                        <h4 class="hover title"><a href="tutor.php">使用手冊</a></h4>
                        <p class="description">不知道如何開始嗎? 點擊此處看看教學!</p>
                    </div>
                </div>



            </div>
        </div>
    </section><!-- End Hero -->

    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
