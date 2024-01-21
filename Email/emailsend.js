// Importiere das Nodemailer-Modul, um E-Mails zu versenden
const nodemailer = require('nodemailer');

// Erstelle einen Transporter für das Versenden von E-Mails
const transporter = nodemailer.createTransport({
    service: 'Gmail', // Verwende den Namen deines E-Mail-Dienstes (hier: Gmail)
    auth: {
        user: 'deine-email@gmail.com', // Deine E-Mail-Adresse
        pass: 'dein-passwort' // Dein Passwort oder ein App-Passwort, wenn du Gmail verwendest
    }
});

// Definiere die E-Mail-Optionen
const mailOptions = {
    from: 'deine-email@gmail.com', // Absender-E-Mail-Adresse
    to: 'empfaenger@example.com', // Empfänger-E-Mail-Adresse
    subject: 'Test-E-Mail', // Betreff der E-Mail
    text: 'Dies ist eine Test-E-Mail von meinem Lokalhost-Projekt.' // Textinhalt der E-Mail
};

// Sende die E-Mail mithilfe des Transporters
transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
        console.error('Fehler beim Senden der E-Mail:', error); // Bei einem Fehler, gebe eine Fehlermeldung aus
    } else {
        console.log('E-Mail erfolgreich gesendet:', info.response); // Wenn erfolgreich, gebe die Bestätigung aus
    }
});

// Hinweis: Die Nutzung dieses Codes erfordert zusätzliche Einrichtung und Konfiguration des Servers,
// daher wurde er nur als Beispiel für mögliche Verwendungszwecke eingebunden.
