<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="Site.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css" type="text/css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
</head>

<body>
    <header>
    </header>
    <div class="block">
        <form method="POST" id="form">
            <div class="txt">
                <h2> Форма </h2>
            </div>
            <label>
                Введите ваше имя:<br />
                <input type="text" name="first-name" placeholder="Савва" />
            </label><br />
            <label>
                Введите ваш Email:<br />
                <input name="field-email" placeholder="test@example.com" type="email">
            </label><br />
            <label>
                Дата рождения:<br />
                <input name="field-date" value="2022-05-25" type="date" />
            </label><br /> Пол:
            <label><input type="radio" checked="checked" name="radio-sex" value="0" />
                Мужской</label>
            <label><input type="radio" name="radio-sex" value="1" />
                Женский</label><br /> Кол-во конечностей:
            <label><input type="radio" checked="checked" name="radio-limb" value="0" />
                0</label>
            <label><input type="radio" name="radio-limb" value="1" />
                1</label>
            <label><input type="radio" name="radio-limb" value="2" />
                2</label>
            <label><input type="radio" name="radio-limb" value="3" />
                3</label>
            <label><input type="radio" name="radio-limb" value="4" />
                4</label><br />
            <label>
                Ваши сверхспособности:<br />
                <select name="superpower[]" multiple=multiple>
                    <option value="Бессмертие">Бессмертие</option>
                    <option value="Левитация">Левитация</option>
                    <option value="Просыпаться к первой паре">Просыпаться к первой паре</option>
                </select>
            </label><br />

            <label>
                Биография:<br />
                <textarea name="BIO" placeholder="Расскажите о себе"></textarea>
                <br />
            </label>

            <label>
                <input name="ch" type="checkbox" checked=checked value=1> Ознакомлен с контрактом:<br />
            </label>

            <input type="submit" value="Отправить" />
        </form>
    </div>
    <footer>
        <h1>(c)Коваленко Савва</h1>
    </footer>

</body>

</html>