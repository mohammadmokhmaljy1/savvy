<!DOCTYPE html>
<html lang="ar-sy">

<head>
    <?php include_once "includes/head.php"; ?>
</head>

<body>
    <div class="container f-cen bg-45">
        <form action="" method="post" autocomplete="on" id="login-form" class="g24 f-cen f-col bg-secondary p24 r12 box-shadow">
            <img src="../assets/images/logo.jpg" alt="" style="width: 100px;" class="r50">

            <h2>تسجيل الدخول</h2>

            <div class="input-box">
                <input type="email" name="email" id="email" required tabindex="1" autofocus autocomplete="email">
                <label for="email">البريد الالكتروني</label>
            </div>

            <div class="input-box">
                <input type="password" name="password" id="password" required tabindex="2" autocomplete="current-password">
                <label for="password">كلمة المرور</label>
            </div>

            <button name="login" class="btn w-100" type="submit">تسجيل الدخول</button>

            <?php
            // كود تسجيل الدخول من هنا
            if (isset($_POST["login"])) {
                include_once "../BL/Employees.php";
                Employees:
                $e = new Employees();
                $email = $_POST["email"];
                $pass = $_POST["password"];
                $data = $e->login($email, $pass);
                if ($data === "EMAIL_NOT_FOUND") {
                    echo "الحساب غير موجود";
                } else {
                    if ($data === "INVALID_PASSWORD") {
                        echo "كلمة المرور خاطئة";
                    } else {
                        if ($data) {
                            session_start();
                            $_SESSION["email"] = $data["email"];
                            $_SESSION["name"] = $data["name"];
                            $_SESSION["position"] = $data["position"];
                            header("./");
                        }
                    }
                }
            }
            ?>
        </form>
    </div>

    <script src="../assets/scripts/validation.js"></script>
</body>

</html>