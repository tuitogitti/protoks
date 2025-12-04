/** 
 * Server.js on pieni palvelinpuolen Node/Express REST-API -sovellus, jolla 
 * haetaan vastauksia GPT5-kielimallilta Openai:n API:n välityksellä.
 * 
 * REST-API:ssa on kaksi päätepistettä:
 * 
 *  /api/getpoem - asiakas (client.js) pyytää runoa
 *  /api/getpic - asiakas (client.js) pyytää kuvaa
 * 
 * Runopyyntö tuottaa asiakkaan antamasta kuvauksesta promptin, joka annetaan 
 * kielimallille, joka rakentaa runon Openai:n vector storessa olevien runojen 
 * pohjalta. 
 * 
 * Kuvapyyntö tuottaa asiakkaan antamasta kuvauksesta promptin, joka annetaan
 * kielimallille, joka hakee kuvauksen perusteella sitä semanttisesti lähimpänä
 * olevan kuvatekstin vector storesta ja valitsee kuvan sen perusteella.
 * 
 * Server.js:n tuottama data palautetaan asiakassovellukseen (client.js), jossa
 * generoitu runo ja sitä semanttisesti parhaiten vastaava kuva esitetään.
*/

// Tuodaan tarvittavat kirjastot
require('dotenv').config();
const { OpenAI } = require('openai');
const express = require('express');
const cors = require('cors');

// Alustetaan Express-sovellus
const app = express();
const port = 3000;

// Middleware
// Tarvitaan JSON-muotoisten pyyntöjen käsittelyyn
app.use(express.json()); 
// Sallii cross-origin pyynnöt frontendiltä
// Tähän voidaan liittää tarkka frontendin osoite, kun se tiedetään.
app.use(cors());

// Ladataan Openai:n API-key ympäristömuuttujasta
const API_KEY = process.env.OPENAI_API_KEY;

if (!API_KEY) {
  console.error('Virhe: OPENAI_API_KEY:tä ei ole.');
  process.exit(1);
}
// Luo uusi Openai-instanssi
const client = new OpenAI({
  apiKey: process.env['OPENAI_API_KEY'],
});

/************************************
1. RUNON LUONTI
*************************************/

// API-päätepiste, josta asiakas hakee runon
app.post('/api/getpoem', async (req, res) => {
  try {
    // 1. Haetaan model, kuvaus, kohdennus ja pituus pyynnön rungosta
    const { model, desc, focus, maxlen } = req.body;

    const prompt = `Luo aineiston pohjalta isäinpäiväruno, joka perustuu seuraavaan aiheeseen: "${desc}". Kohdenna runo ${focus}. Palauta vain yksi runo, joka on pituudeltaan alle ${maxlen} sanaa, äläkä mitään muuta.`;

    // 2. Lähetetään prompti Openai:lle
    const response = await client.responses.create({
      model: model,
      input: prompt,
      tools: [
        {
          type: 'file_search', // haku vector-storesta
          vector_store_ids: [process.env['VS1_ID']],
        },
      ],
    });
    const poem = response.output_text;

    // 3. Palautetaan runo JSON-oliossa
    res.json({ success: true, poem: poem });
  } catch (error) {
    console.error('Virhe generointiprosessissa:', error);
    res.status(500).json({
      success: false,
      message: 'Virhe runon luomisessa.',
      error: error.message,
    });
  }
});

/************************************
2. RUNOON LIITTYVÄN KUVAN HAKU
*************************************/

// API-päätepiste, josta asiakas hakee kuvan
app.post('/api/getpic', async (req, res) => {
  try {
    // 1. Haetaan model ja kuvaus pyynnön rungosta
    const { model, desc } = req.body;

    const prompt = `Etsi rivi, jossa oleva teksti vastaa parhaiten tekstiä "${desc}" ja palauta riviltä .jpg-päätteinen kuvan nimi, äläkä mitään muuta.`;

    // 2. Lähetetään prompti Openai:lle
    const response = await client.responses.create({
      model: model,
      input: prompt,
      tools: [
        {
          type: 'file_search', // haku vector-storesta
          vector_store_ids: [process.env['VS2_ID']],
        },
      ],
    });

    const pic = response.output_text;

    // 3. Palautetaan kuvan nimi JSON-oliossa
    res.json({ success: true, pic: pic });
  } catch (error) {
    console.error('Virhe hakuprosessissa:', error);
    res.status(500).json({
      success: false,
      message: 'Virhe tiedon semanttisessa haussa.',
      error: error.message,
    });
  }
});

// Käynnistetään palvelin
app.listen(port, () => {
  console.log(`REST-API palvelin portissa http://localhost:${port}`);
});
