using UnityEngine;
using System.Collections;
using System;

public class Mapal : MonoBehaviour 
{
	bool yacargue;
	LatLongStruct[] arr ,arrDist, arrRank, arrCat;
	public GUIStyle btnAddr, rankBack, rankMini;
	Texture2D catAb, catCerr, catEmpr, categoria;
	bool primero = true;

	public GUIStyle  btnTipo, btnRank;
	bool rankFilter, typeFilter;
	Texture2D tagFilter;
	
	public void show()
	{	
		if (primero) {
			btnAddr.normal.background = (Texture2D)Resources.Load ("btn_AP");
			btnAddr.fontSize = (int) (Screen.width * 0.038f);
			btnAddr.normal.textColor = Color.white;
			btnAddr.alignment = TextAnchor.MiddleLeft;
			catAb = (Texture2D)Resources.Load ("catOpen");
			catEmpr = (Texture2D)Resources.Load ("catEmpr");
			catCerr = (Texture2D)Resources.Load ("catCerr");
			rankBack.normal.background = (Texture2D)Resources.Load("rankBack");
			rankBack.fontSize = (int) (Screen.width * 0.04f);
			rankBack.normal.textColor = Color.white;
			rankBack.alignment = TextAnchor.MiddleCenter;

			rankMini.fontSize = (int) (Screen.width * 0.02f);
			rankMini.normal.textColor = Color.white;
			rankMini.alignment = TextAnchor.LowerCenter;

			rankFilter=false;
			typeFilter=false;
			tagFilter= (Texture2D)Resources.Load ("filtrarPor");

			btnTipo.hover.background = btnTipo.active.background = (Texture2D)Resources.Load ("btnTipo_A");
			
			btnTipo.normal.background = (Texture2D)Resources.Load ("btnTipo");
			
			btnRank.hover.background = btnRank.active.background = (Texture2D)Resources.Load ("btnRanking_A");
			
			btnRank.normal.background = (Texture2D)Resources.Load ("btnRanking");

			primero = false;
		}

		if (rankFilter) 
		{	
			btnRank.normal.background = (Texture2D)Resources.Load ("btnRanking_A");
			arr = arrRank;

		}
		else
			btnRank.normal.background = (Texture2D)Resources.Load ("btnRanking");
		
		if(typeFilter){
			btnTipo.normal.background = (Texture2D)Resources.Load ("btnTipo_A");
			arr = arrCat;
		}
		else
			btnTipo.normal.background = (Texture2D)Resources.Load ("btnTipo");

		if (!rankFilter && !typeFilter)
			arr = arrDist;
		
		int aux = 0;
		if (!yacargue && GetComponent<DataAccessPoints>().getLatitudes().Length == 9) 
		{
			Debug.Log("DONE");

			arr = GetComponent<DataAccessPoints>().getLatitudes();

			arrDist = arr.Clone() as LatLongStruct[];
			arrCat =  arr.Clone() as LatLongStruct[];
			arrRank =  arr.Clone() as LatLongStruct[];

			Debug.Log ("ARRRANK 0 " + arrRank[0].nombre + "\n " + arrRank[0].rango);

			
			Array.Sort(arrRank, (x, y) => y.rango.CompareTo(x.rango) );
			Array.Sort(arrCat, (x, y) => y.cat.CompareTo(x.cat) );

			//arrRank = orderByRank(arr);
			
			Debug.Log ("ARRRANK 1 " + arrRank[0].nombre + "\n " + arrRank[0].rango);

			aux = GetComponent<DataAccessPoints>().getLatitudes().Length;
			
			yacargue = true;
			
			PlayerPrefs.SetString("accespoints", GetComponent<DataAccessPoints>().getData());
		}
		
		if(yacargue == true)
		{
			GUI.DrawTexture(onScreen(.1f,.15f,.8f,(9f/16f)*.1f), (Texture2D)Resources.Load("titleList"));

			float y = .23f;

			GUI.DrawTexture(onScreen(.4f, .25f, .181f, (9f/16f)*.053f), tagFilter);

			if(GUI.Button(onScreen(.6f, .25f, .181f, (9f/16f)*.053f), "", btnRank))
			{
				rankFilter=!rankFilter;
				if (typeFilter && rankFilter)
					typeFilter = !typeFilter;
				
			}
			if(GUI.Button(onScreen(.8f, .25f, .118f, (9f/16f)*.053f), "", btnTipo))
			{
				typeFilter=!typeFilter;
				if (rankFilter && typeFilter)
					rankFilter=!rankFilter;
				
			}

			for(int i = 0; i < 9; i++)
			{
				if(arr[i].cat == "espacio abierto")
					GUI.DrawTexture(onScreen(.07f, y+=.07f, .06f*16f/9f, .06f), catAb);
				if(arr[i].cat == "espacio cerrado")
					GUI.DrawTexture(onScreen(.07f, y+=.07f, .06f*16f/9f, .06f), catCerr);
				if(arr[i].cat == "empresa")
					GUI.DrawTexture(onScreen(.07f, y+=.07f, .06f*16f/9f, .06f), catEmpr);

				if(GUI.Button(onScreen(.18f, y, .645f, .06f), "  "+arr[i].nombre, btnAddr))
				{
					//preguntar
					GetComponent<Gps>().lonselec = arr[i].getLatitude();
					Debug.Log("esto es texto: "+arr[i].getLongitude() + " esto es parseado" + float.Parse(arr[i].getLongitude()));
					GetComponent<Gps>().latselec = arr[i].getLongitude();
					Debug.Log("lon para seleccion: "+arr[i].getLongitude()+"NOMBRE: "+arr[i].nombre);
					GetComponent<Gps>().votos = arr[i].votos;
					GetComponent<Gps>().ranking = arr[i].rango;
					Debug.Log("seleccion: "+GetComponent<Gps>().lonselec + "" + GetComponent<Gps>().latselec);
					
					GetComponent<Mapao>().primer = true;
					GetComponent<Gps>().v = views.MAPAO;
				}

				GUI.Label(onScreen(.83f, y, .1f, .06f), "" +  arr[i].rango.ToString("0.00"), rankBack);
				GUI.Label(onScreen(.83f, y, .1f, .055f), "Ranking", rankMini);
			}	
		}
	}
	Rect onScreen(float x, float y, float w, float h) {return new Rect(Screen.width*x, Screen.height*y, Screen.width*w, Screen.height*h);}
}
