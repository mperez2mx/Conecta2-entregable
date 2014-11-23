using UnityEngine;
using System.Collections;

public class Menu : MonoBehaviour
{
	public GUIStyle btnMapa, btnRealidad, btnBrujula, btnEvalua, btnHome, btnSitio;

	bool primer=true;

	Texture2D background;



	public void show() 
	{	
		Gps main = GetComponent<Gps>();

		if (primer) 
		{


			background = (Texture2D)Resources.Load ("background");



			btnMapa.hover.background = 	btnMapa.active.background = (Texture2D)Resources.Load ("btn_map_A");

			btnBrujula.hover.background = btnBrujula.active.background = (Texture2D)Resources.Load ("btn_bruj_A");

			btnSitio.hover.background = btnSitio.active.background = (Texture2D)Resources.Load("btn_sitio_A");

			btnSitio.normal.background = (Texture2D)Resources.Load("btn_sitio");
			
			btnEvalua.hover.background= btnEvalua.active.background= (Texture2D)Resources.Load ("btn_ev_A");

			btnRealidad.hover.background= btnRealidad.active.background= (Texture2D)Resources.Load ("btn_ra_A");
			
			btnHome.normal.background = (Texture2D)Resources.Load ("btn_home");


			primer=false;
		}

		if(main.v == views.REALIDAD)
			btnRealidad.normal.background = (Texture2D)Resources.Load ("btn_ra_A");
		else
			btnRealidad.normal.background = (Texture2D)Resources.Load ("btn_ra");

		if(main.v == views.MAPAO)
			btnMapa.normal.background = (Texture2D)Resources.Load ("btn_map_A");
		else
			btnMapa.normal.background = (Texture2D)Resources.Load ("btn_map");
		
		if (main.v == views.BRUJULA)
			btnBrujula.normal.background = (Texture2D)Resources.Load ("btn_bruj_A");
		else
			btnBrujula.normal.background = (Texture2D)Resources.Load ("btn_bruj");
		
		if (main.v == views.EVAL)
			btnEvalua.normal.background = (Texture2D)Resources.Load ("btn_ev_A");
		else
			btnEvalua.normal.background = (Texture2D)Resources.Load ("btn_ev");



		if(GUI.Button(new Rect(0f, Screen.height*.01f, Screen.height*.1f, Screen.height*.1f), "", btnHome)) main.v = views.MAPAL;

		if(main.v == views.MAPAL)
		{


		}
			else
		{
			GUI.DrawTexture(onScreen(.0f, .9f, Screen.width, .1f), background);


			if(GUI.Button(new Rect(0f, Screen.height*.9f, Screen.height*.1f, Screen.height*.1f), "", btnMapa)) main.v = views.MAPAO;
			if(GUI.Button(new Rect(Screen.height*.1f, Screen.height*.9f, Screen.height*.1f, Screen.height*.1f), "", btnRealidad)) main.v = views.REALIDAD;
			if(GUI.Button(new Rect(Screen.height*.2f, Screen.height*.9f, Screen.height*.1f, Screen.height*.1f), "",btnBrujula)) main.v = views.BRUJULA;
			if(GUI.Button(new Rect(Screen.height*.3f, Screen.height*.9f, Screen.height*.1f, Screen.height*.1f), "",btnEvalua)) main.v = views.EVAL;
			if(GUI.Button(new Rect(Screen.height*.4f, Screen.height*.9f, Screen.height*.1f, Screen.height*.1f), "",btnSitio)) Application.OpenURL("http://miguelp.com/Conecta2/sitio/");

		}
	}

	Rect onScreen(float x, float y, float w, float h) {return new Rect(Screen.width*x, Screen.height*y, Screen.width*w, Screen.height*h);}
}
