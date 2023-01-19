<h1>Invoice CS-Cart(4.7.1) Plugin</h1>

<h3>Установка</h3>

1. [Скачайте плагин](https://github.com/Invoice-LLC/Invoice.Module.CSCart/archive/master.zip) и скопируйте содержимое архива в корень сайта
2. Перейдите во вкладку **Модули->Управление модулями**, найдите плагин Invoice и нажмите установить
![Imgur](https://imgur.com/yLBLjn1.png)
3. Перейдите во вкладу **Администрирование->Способы оплаты**, нажмите "+"
![Imgur](https://imgur.com/bNlfJLH.png)
4. Заполните форму(Название: "Invoice", Процессор: "Invoice"), затем нажмите создать
![Imgur](https://imgur.com/pglP6wx.png)
5. Перейдите во вкладку "Настройки" способа оплаты "Invoice" и введите свои данные из [личного кабинета](https://lk.invoice.su/)
<br>Api ключи и Merchant Id:<br>
![image](https://user-images.githubusercontent.com/91345275/196218699-a8f8c00e-7f28-451e-9750-cfa1f43f15d8.png)
![image](https://user-images.githubusercontent.com/91345275/196218722-9c6bb0ae-6e65-4bc4-89b2-d7cb22866865.png)<br>
<br>Terminal Id:<br>
![image](https://user-images.githubusercontent.com/91345275/196218998-b17ea8f1-3a59-434b-a854-4e8cd3392824.png)
![image](https://user-images.githubusercontent.com/91345275/196219014-45793474-6dfa-41e3-945d-fc669c916aca.png)<br>
6. Добавьте уведомление в личном кабинете Invoice(Вкладка Настройки->Уведомления->Добавить)
с типом **WebHook** и адресом: **%URL сайта%/invoice/callback.php**<br>
![Imgur](https://imgur.com/lMmKhj1.png)
