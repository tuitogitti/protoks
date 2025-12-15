import { Component, input, OnInit } from '@angular/core';
import { Model } from 'survey-core';
import { SurveyModule } from 'survey-angular-ui';

@Component({
  selector: 'app-survey',
  imports: [SurveyModule],
  templateUrl: './survey.html',
  styleUrl: './survey.css',
})
export class Survey implements OnInit {
  // Kyselydata otetaan sisään komponenttiin
  surveyJson = input();
  surveyModel!: Model;

  ngOnInit() {
    // Kysely rakennetaan
    const survey = new Model(this.surveyJson());
    survey.onComplete.add(this.alertAndSaveResults);
    this.surveyModel = survey;
  }
  // Kyselyn tulokset lähetetään palvelimelle
  alertAndSaveResults(sender: Model) {
    const results = JSON.stringify(sender.data);
    // alert(results);
    // Vaihda tähän oman backendisi url
    saveSurveyResults('http://localhost/mt_backend/index.php', results);
  }
}
/*
Tulosten tallennus palvelimelle. Jostain syystä Surveyjs-kirjasto
ei hyväksynyt servicen käyttöä, vaan tämän pitää olla komponentissa.
*/
function saveSurveyResults(url: string, json: string) {
  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
    },
    body: json,
  })
    .then((response) => response.text())
    .then((data) => {
      console.log('Vastaus PHP:ltä: ' + data);
    })
    .catch((error) => {
      console.log('Virhe lähetettäessä: ' + error);
    });
}
