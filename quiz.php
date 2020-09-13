<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Review</title>

    <link rel="stylesheet" href="styles/prism.css">
    <link rel="stylesheet" href="styles/quiz.css">
    <link rel="stylesheet" href="styles/mcradio.css">
    <link rel="stylesheet" href="type/ionicfont/ionicons.css">	

    <!-- Main JS -->
    <script src="scripts/prism.js"></script>
    <script src="scripts/quiz.js"></script>

    <!-- Explanation Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="styles/test.css" />
</head>

<body>
    <div class="question">
        <!-- <div class="spacer"></div> -->
        <div class="code">
            <pre><code spellcheck="false" class="language-java line-numbers"></code></pre>
        </div>
        <form id="question" action="" method="post">
            <h1 class="qtext">&nbsp;</h1>
            <input type="radio" id="a" name="radio"><label class="ans a" for="a" onclick="select('a')">&nbsp;</label>
            <input type="radio" id="b" name="radio"><label class="ans b" for="b" onclick="select('b')">&nbsp;</label>
            <input type="radio" id="c" name="radio"><label class="ans c" for="c" onclick="select('c')">&nbsp;</label>
            <input type="radio" id="d" name="radio"><label class="ans d" for="d" onclick="select('d')">&nbsp;</label>
            <input type="radio" id="e" name="radio"><label class="ans e" for="e" onclick="select('e')">&nbsp;</label>
            <input type="hidden" id="ans" name="choice" value="" />
            <div class="controller">
                <label class="ctrl sub">
                    <input type="submit" name="sub" />
                    <p class="submit-text">Check</p>
                </label>
                <!-- <label class="ctrl next">
                    <input type="submit" name="next" />
                    <p>&#xf125</p>
                </label> -->
            </div>
            <p class="explain" href="#explain" rel="modal:open">Explanation</a>
            <p class="explainbody">&nbsp;</p>
        </form>
        <div id="explain" class="modal">
            <h1 class="mod">Explanation</h1>
            <p class="mod">&nbsp;This is an explanation. Filler text is cool. Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad, exercitationem consectetur! Possimus animi molestias voluptas consequuntur velit accusantium quia numquam?</p>
            <a class="button" href="#" rel="modal:close">Okay.</a>
        </div>
    </div>
</body>

</html>
