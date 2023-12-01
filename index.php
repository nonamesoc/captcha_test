<?php

require_once 'src/Connection.php';

session_start();
$form = $_SESSION['form'] ?? null;
unset($_SESSION['form']);
$notice = $_SESSION['notice'] ?? null;
unset($_SESSION['notice']);


$page = $_GET['page'] ?? 0;
$limit = 10;
$db = new Connection();
$messages = $db->select('messages', [
  'title',
  'text',
], $limit, $page * $limit, 'id');

$result = $db->select('messages', ['COUNT(*) as count']);
$count = empty($result[0]['count']) ? 0 : $result[0]['count'];
$last_page = $count / $limit;
?>

<div>
    <?php if (!empty($notice)): ?>
        <span><b><?=$notice?></b></span>
    <?php endif;?>
    <h1>Форма обратной связи</h1>
    <form action="/create_message.php" method="post">
        <div>
            <label for="title">Заголовок:</label>
            <input type="text" id="title" name="title" required <?=(!empty($form['values']['title'])) ? 'value="' . $form['values']['title'] . '"' : ''?>>
        </div>
        <div>
            <label for="message">Сообщение:</label>
            <textarea id="message" name="message" required ><?=(!empty($form['values']['title'])) ? $form['values']['title'] : ''?></textarea>
        </div>
        <div>
            <label for="captcha_code">Введите код с картинки: </label><img src="captcha.php">
            <input type="text" name="captcha_code" id="captcha_code" required>
            <?php if (!empty($form['errors']['captcha_code'])): ?>
                <span><b><?= $form['errors']['captcha_code'] ?></b></span>
            <?php endif;?>
        </div>
        <input type="submit" name="submit" value="Отправить">
    </form>
</div>

<?php if (!empty($messages)): ?>
    <div>
        <h2>Список сообщений</h2>
        <?php foreach ($messages as $message): ?>
            <div>
                <h3><?= $message['title'] ?></h3>
                <div><?= $message['text'] ?></div>
            </div>
        <?php endforeach;?>

        <?php if ($count > $limit): ?>
        <style>
            ul {
                display: block;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            li {
                display: inline-block;
                list-style-type: none;
                margin-right: 10px;
            }
        </style>
            <nav>
                <ul>
                    <?php for ($i = 0; $i < $last_page; $i++): ?>
                        <li>
                            <?php if ($i == $page): ?>
                                <b>
                            <?php endif;?>

                            <a href="?page=<?=$i;?>"><?=$i + 1;?></a>

                            <?php if ($i == $page): ?>
                                </b>
                            <?php endif;?>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif;?>
    </div>
<?php endif;?>
