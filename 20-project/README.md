# Projekt - indexování produktů

Cílem je zaindexovat produkty pomocí indexovací service.

* vzhledem k tomu, že indexování chvilku trvá, tak ho nejde dělat rovnou v `create-products.php`, protože by brzdilo import
* proto si ID produktů k indexování budeme posílat skrz Rabbita

## Zadání
* pomocí `rabbit-install.php` si vytvořte frontu v Rabbitovi (tenhle soubor neobsahuje žádnou záludnost)
* pomocí `create-products.php` si pošlete do Rabbita IDčka ke zpracování
* dodělejte `consumer.php`, aby správně reagoval na různé chybové stavy (ack, reject, requeue?)
   * indexovací service nemusí někdy odpovědět korektně
   * občas se stane i něco dalšího
* řádky označené komentářem _"neměnit"_ prosím needitujte, připravili byste se o zábavu
* abychom indexování zrychlili, tak by bylo vhodné pomocí supervisor spustit alespoň 5 consumerů zároveň
