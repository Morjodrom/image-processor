<style>
    [type="text"],
    [type="number"]{
        display: block;
        width: 100%;
        height: 30px;
        border-radius: 5px;
    }

    .grid {
        display: grid;
        grid-template-columns: 300px 300px;
        column-gap: 36px;
        row-gap: 20px;
    }
    datalist {
        display: flex;
    }
    option {
        display: block;
    }

    .block {
        display: block;
    }

    button {
        margin-top: 20px;
        font-size: 24px;
        background: blue;
        color: #fff;
        border-radius: 5px;
        border: none;
        padding: 15px 24px;
    }
</style>
<form class="form" method="get">
    <div class="grid">
        <label>
            <span class="block">Прозрачность</span>
            <input type="range" min="0.05" max="1" step="0.05" value="0.5" list="opacityMarkers" name="opacity">


            <datalist id="opacityMarkers">
                <option value="0.05"></option>
                <option value="0.25"></option>
                <option value="0.50"></option>
                <option value="0.75"></option>
                <option value="1"></option>
            </datalist>
        </label>


        <label>
            <span class="block">Повторять водяной знак</span>
            <input type="number" name="full" value="1">
        </label>


        <label>
            <span class="block">Стартовая цифра идентификатора</span>
            <input type="text" name="startIndex" value="1">
        </label>

        <label class="block">
            <input type="checkbox" name="startIndex" value="-1">
            <span>Не указывать идентификатор</span>
        </label>

        <label>
            <span class="block">Максимальная сторона</span>
            <input type="number" min="500" step="4" value="1024">
        </label>


        <label>
            Сохранять при обработке
            <input type="checkbox" name="preserve" value="1" checked>
        </label>
    </div>

    <button type="submit">Отправить</button>

</form>

<?php

