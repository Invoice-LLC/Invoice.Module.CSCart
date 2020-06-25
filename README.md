<h1>Invoice CS-Cart(4.7.1) Plugin</h1>

<h3>Установка</h3>

1. [Скачайте плагин](https://github.com/Invoice-LLC/Invoice.Module.OpenCart/archive/master.zip) и скопируйте содержимое архива в корень сайта
2. Перейдите во вкладку **Модули->Управление модулями**, найдите плагин Invoice и нажмите установить
![Imgur](https://imgur.com/yLBLjn1.png)
3. Перейдите во вкладу **Администрирование->Способы оплаты**, нажмите "+"
![Imgur](https://imgur.com/bNlfJLH.png)
4. Заполните форму(Название: "Invoice", Процессор: "Invoice"), затем нажмите создать
![Imgur](https://imgur.com/pglP6wx.png)
5. Перейдите во вкладку "Настройки" способа оплаты "Invoice" и введите свои данные из [личного кабинета](https://lk.invoice.su/)
6. Добавьте уведомление в личном кабинете Invoice(Вкладка Настройки->Уведомления->Добавить)
с типом **WebHook** и адресом: **%URL сайта%/invoice/callback.php**<br>
![Imgur](https://imgur.com/lMmKhj1.png)
