export const surveyjson1 = {
  title: 'Palautekysely',
  pageNextText: 'Seuraava',
  pagePrevText: 'Takaisin',
  completeText: 'Lähetä',
  pages: [
    {
      elements: [
        {
          type: 'text',
          name: 'Tunnistautuminen',
          title: 'Kirjoita tähän palvelukotisi nimi',
        },
      ],
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Miten vastaat kyselyyn',
          title: 'Olen vastaamassa kyselyyn',
          choices: [
            'Itsenäisesti ilman muiden apua',
            'Läheisen kanssa',
            'Tutun hoitajan kanssa',
            'Muun hoitajan tai avustajan kanssa',
            'Kysymyksiin vastasi asiakkaan luvalla läheinen',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'rating',
          rateMin: 1,
          rateMax: 5,
          name: 'Suosittelisinko palvelukotia',
          title:
            'Kuinka todennäköisesti suosittelisit palvelukotia läheisellesi?',
          mininumRateDescription: 'En suosittelisi',
          maximumRateDescription: 'Suosittelisin lämpimästi',
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Toiveitani otetaan huomioon',
          title:
            'Toiveitani ja palautettani huomioidaan palvelukodin hoivassa ja palveluissa',
          choices: [
            'Täysin samaa mieltä',
            'Melko samaa mieltä',
            'En osaa sanoa',
            'Melko eri meltä',
            'Täysin eri mieltä',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Saan tarpeeksi apua',
          title: 'Saan arjessani riittävästi apua ja palvelua',
          choices: [
            'Täysin samaa mieltä',
            'Melko samaa mieltä',
            'En osaa sanoa',
            'Melko eri meltä',
            'Täysin eri mieltä',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Hoitajilla on tarpeeksi aikaa minulle',
          title: 'Hoitajilla on tarpeeksi aikaa minulle',
          choices: [
            'Täysin samaa mieltä',
            'Melko samaa mieltä',
            'En osaa sanoa',
            'Melko eri meltä',
            'Täysin eri mieltä',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Minulla on yksinäinen olo',
          title: 'Minulla on yksinäinen olo',
          choices: ['Ei koskaan', 'Harvoin', 'Usein', 'En osaa sanoa'],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Minulla on turvallinen olo',
          title: 'Minulla on turvallinen olo',
          choices: [
            'Täysin samaa mieltä',
            'Melko samaa mieltä',
            'En osaa sanoa',
            'Melko eri meltä',
            'Täysin eri mieltä',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Virkistystoimintaa on tarpeeksi',
          title:
            'Minulle on tarjolla riittävästi virkistystoimintaa josta pidän. Esimerkiksi pelejä, visailua, musiikkia, toiminnallista tekemistä, jumppaa',
          choices: [
            'Täysin samaa mieltä',
            'Melko samaa mieltä',
            'En osaa sanoa',
            'Melko eri meltä',
            'Täysin eri mieltä',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Pääsen ulkoilemaan',
          title: 'Pääsen ulkoilemaan tarpeeksi usein',
          choices: [
            'Täysin samaa mieltä',
            'Melko samaa mieltä',
            'En osaa sanoa',
            'Melko eri meltä',
            'Täysin eri mieltä',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Olen tyytyväinen ruokaan',
          title: 'Olen tyytyväinen tarjolla olevaan ruokaan',
          choices: [
            'Täysin samaa mieltä',
            'Melko samaa mieltä',
            'En osaa sanoa',
            'Melko eri meltä',
            'Täysin eri mieltä',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'radiogroup',
          name: 'Olen tyytyväinen nykyiseen elämääni',
          title: 'Olen tyytyväinen nykyiseen elämääni',
          choices: [
            'Täysin samaa mieltä',
            'Melko samaa mieltä',
            'En osaa sanoa',
            'Melko eri meltä',
            'Täysin eri mieltä',
          ],
        },
      ],
      isRequired: true,
      requiredErrorText: 'Valitse yksi vaihtoehto',
    },
    {
      elements: [
        {
          type: 'comment',
          maxLength: 512,
          rows: 6,
          autoGrow: true,
          name: 'Vapaa palaute',
          title:
            'Voit vielä kertoa lisää ja antaa avointa palautetta palvelukodissa asumisesta. Kerro vapaasti ajatuksesi ja mielipiteesi. Paina lopuksi "Lähetä" -nappia',
        },
      ],
    },
  ],
  completedHtml: '<h3>Kiitos vastauksesta!</h3>',
};
