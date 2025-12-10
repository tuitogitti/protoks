import { Component, OnInit } from '@angular/core';
import { SurveyComponent } from './survey/survey.component';
import { surveyjson1 } from '../surveydata/surveyjson1';

@Component({
  selector: 'app-root',
  imports: [SurveyComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css',
})
export class AppComponent implements OnInit {
  surveyJson: any = {};

  ngOnInit() {
    /* kysely on "kovakoodattuna" frontendissä surveydata-kansiossa.
	   Se voitaisiin myös hakea palvelimelta, missä sitä voitaisiin muokata.
	*/
    this.surveyJson = surveyjson1;
  }
}
