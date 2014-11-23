using UnityEngine;
using System.Collections;

public class Evaluacion : MonoBehaviour 
{
	public GUIStyle STAR, star, reportar;
	public GUIStyle s;
	bool primer = true;
	int tocada = 0;
	bool ocupado;
	WWW www;

	public void show()
	{
		if(primer)
		{
			reportar.normal.background = (Texture2D)Resources.Load("reportar");
			reportar.active.background = (Texture2D)Resources.Load("reportar_A");
			STAR.normal.background = (Texture2D)Resources.Load("fullStar");
			star.normal.background = (Texture2D)Resources.Load("emptyStar");
			s.normal.textColor = new Color(.375f, .375f, .375f);
			s.fontSize = 60;
			s.alignment = TextAnchor.MiddleCenter;
			primer = false;
		}

		GUI.Label(onScreen(.2f, .12f, .6f, .2f), "Evalúa este punto de acceso", s);

		if(GUI.Button(onScreen(.08f, .33f, .16f, .09f), "", tocada >=1?STAR:star )) StartCoroutine(evalua(tocada = 1));
		if(GUI.Button(onScreen(.25f, .33f, .16f, .09f), "", tocada >=2?STAR:star)) StartCoroutine(evalua(tocada = 2));
		if(GUI.Button(onScreen(.42f, .33f, .16f, .09f), "", tocada >=3?STAR:star)) StartCoroutine(evalua(tocada = 3));
		if(GUI.Button(onScreen(.59f, .33f, .16f, .09f), "", tocada >=4?STAR:star)) StartCoroutine(evalua(tocada = 4));
		if(GUI.Button(onScreen(.76f, .33f, .16f, .09f), "", tocada >=5?STAR:star)) StartCoroutine(evalua(tocada = 5));

		if (tocada == 1 || tocada == 2)
		{
			GUI.Label(onScreen(.2f, .45f, .6f, .2f), "Has elegido una mala calificación.\nDeseas reportar este punto de acceso?", s);

			if(GUI.Button(onScreen(.3f, .6f, .4f, .07f), "", reportar))
			{
				StartCoroutine(reporta());
			}
		}

	}

	public IEnumerator reporta()
	{
		Debug.Log("reportar");
		if(!ocupado) //es como un semáforo, sin esto, cuando pides dos imágenes muy rápido truena
		{
			ocupado = true;
			www = new WWW("http://www.miguelp.com/conecta2/reportador.php?lat="+GetComponent<Gps>().lonselec+"&lon="+GetComponent<Gps>().latselec);
			yield return www; // yield espera a que termine todo lo de arriba y con return a puedes seguir.
			string tex = www.text;
			Debug.Log(tex);
			ocupado = false;
		}
	}

	public IEnumerator evalua(int cal)
	{
		if(!ocupado) //es como un semáforo, sin esto, cuando pides dos imágenes muy rápido truena
		{
			ocupado = true;
			www = new WWW("http://www.miguelp.com/conecta2/evaluador.php?lat="+GetComponent<Gps>().latselec+"&lon="+GetComponent<Gps>().lonselec+"&votos="+GetComponent<Gps>().votos+"&ranking="+GetComponent<Gps>().ranking+"&tocada="+tocada);
			yield return www; // yield espera a que termine todo lo de arriba y con return a puedes seguir.
			string tex = www.text;
			Debug.Log(tex);
			ocupado = false;
		}
	}
	
	Rect onScreen(float x, float y, float w, float h) {return new Rect(Screen.width*x, Screen.height*y, Screen.width*w, Screen.height*h);}
}
