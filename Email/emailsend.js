const nodemailer = require('nodemailer');

const transporter = nodemailer.createTransport({
    service: 'Gmail', // Verwende den Namen deines E-Mail-Dienstes
    auth: {
        user: 'deine-email@gmail.com', // Deine E-Mail-Adresse
        pass: 'dein-passwort' // Dein Passwort oder ein App-Passwort, wenn du Gmail verwendest
    }
});

const mailOptions = {
    from: 'deine-email@gmail.com',
    to: 'empfaenger@example.com',
    subject: 'Test-E-Mail',
    text: 'Dies ist eine Test-E-Mail von meinem Lokalhost-Projekt.'
};

transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
        console.error('Fehler beim Senden der E-Mail:', error);
    } else {
        console.log('E-Mail erfolgreich gesendet:', info.response);
    }
});
