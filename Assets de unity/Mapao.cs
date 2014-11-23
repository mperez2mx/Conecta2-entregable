using UnityEngine;
using System.Collections;

public class Mapao : MonoBehaviour 
{
	public bool primer;
	public GUIStyle btnPlus, btnMinus;
	float dx, dy;

	public void show()
	{
		Gps main = GetComponent<Gps>();
		btnPlus.normal.background = (Texture2D)Resources.Load ("lupa+");
		btnMinus.normal.background = (Texture2D)Resources.Load ("lupa-");
		
		if(main.tex!=null) GUI.DrawTexture(onScreen(-7.5f/9f+dx/Screen.width, -4f/16f+dy/Screen.height, 24f/9, 24f/16f), main.tex); //dibuja el mapa, tex guarda el mapa

		if (Input.touchCount > 0 && Input.GetTouch(0).phase == TouchPhase.Moved) 
		{
			Vector2 touchDeltaPosition = Input.GetTouch(0).deltaPosition;
			dx+=touchDeltaPosition.x*0.7f;
			dy-=touchDeltaPosition.y*0.7f;
		}
			
		if(primer)
		{ 
			StartCoroutine(main.GetData(0)); 
			primer = false;
		}
		
		if(GUI.Button(new Rect (Screen.width*.85f, Screen.height*.75f, Screen.height*.05f, Screen.height*.05f), "", btnPlus)) 
			StartCoroutine(main.GetData(1));
		if(GUI.Button(new Rect(Screen.width*.85f, Screen.height*.83f, Screen.height*.05f, Screen.height*.05f), "", btnMinus)) StartCoroutine(main.GetData(-1));
	}
	
	Rect onScreen(float x, float y, float w, float h) {return new Rect(Screen.width*x, Screen.height*y, Screen.width*w, Screen.height*h);}

}
