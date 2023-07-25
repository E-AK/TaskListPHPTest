$(document).ready(function () {
    // Обработчик отправки формы для входа в систему
    $("#loginform").click(function (event) {
        event.preventDefault();

        // Получение значений полей ввода
        var login = $("#username").val();
        var password = $("#password").val();

        // Выполнение авторизации / регистрации
        auth(login, password)
            .then(function () {
                // Перенаправление на главную страницу после успешной авторизации
                window.location.href = "/";
            })
            .catch(function (error) {
                console.error(error);
            });
    });
});
