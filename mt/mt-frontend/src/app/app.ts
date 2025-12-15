import { Component, OnInit } from '@angular/core';
import { Survey } from './survey/survey';
import { surveyjson1 } from '../surveydata/surveyjson1';

@Component({
  selector: 'app-root',
  imports: [Survey],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App implements OnInit {
  surveyJson: any = {};
   
  
  ngOnInit() {
    /* kysely on "kovakoodattuna" frontendissä surveydata-kansiossa.
	   Se voitaisiin myös hakea palvelimelta, missä sitä voitaisiin muokata.
	*/
    this.surveyJson = surveyjson1;
  }
}
