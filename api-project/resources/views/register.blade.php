<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<style>
    span {
        color: red;
    }
</style>
<h1>User Register </h1>
<form id="register_form">
    <input type="text" name="name" placeholder="Enter Name">
    <br>
    <br>

    <span class="error name_err"></span>
    <input type="email" name="email" placeholder="Enter mail">
    <br><br>
    <input type="password" name="password" placeholder="Enter Password">
    <input type="password" name="password_confirmation" placeholder="Enter Password">
    <br>
    <br>
    <input type="submit" value="Register">
</form>


<script>
    $(document).ready(function() {
        $("#register_form").submit(function(event) {
            event.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                url: "http://127.0.0.1:8000/api/register",
                type: "POST",
                data: formData,
                success: function(data) {
                    if (data.msg) {

                    } else {
                        prinErrorMsg(data);
                    }
                }
            });
        });

        function prinErrorMsg(msg) {
            $(".error").text("");
            $.each(msg, function(key, value) {

                if (key == 'password') {
                    if (value.lenght > 1) {
                        $(".password_err").text(value[0]);
                        $(".password_confirmation_err").text(value[1]);

                    } else {
                        $("." + key + "_err").text(value)
                    }
                } else {

                    $("." + key + "_err").text(value)
                }
            })

        }
    });
</script>
