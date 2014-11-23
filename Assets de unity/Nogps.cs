using UnityEngine;
using System.Collections;

public class Nogps : MonoBehaviour {
	
	Texture2D background,
	botonReload,
	error;
	
	
	public void show(int errorType)
	{
		background =  (Texture2D)Resources.Load("background");
		botonReload = (Texture2D)Resources.Load("botonReintentar");
		
		switch (errorType) 
		{
		case 1:
			error = (Texture2D)Resources.Load("Error1");
			break;
		case 3:
			error = (Texture2D)Resources.Load("Error3");
			break;
		default:
			error = (Texture2D)Resources.Load("Error2");
			break;
		}
		
		GUI.DrawTexture (new Rect (0, 0, Screen.width, Screen.height), background);
		
		float [] sizeBtn = scaleImg (botonReload, Screen.width * .70f, 0f);
		
		if (GUI.Button (new Rect (Screen.width * .15f, Screen.height * .83f, sizeBtn [0], sizeBtn [1]), botonReload)) 
		{
			
		}
		
		float[] sizeError = scaleImg (error, Screen.width*.4f, 0f);

		GUI.DrawTexture (new Rect (Screen.width * .33f, Screen.height * .25f, sizeError [0], sizeError [1]), error);
		
		GUI.Label(onScreen(.1f, .02f, 1f, 1f), "No fue posible obtener tu ubicación.");
	}
	
	Rect onScreen(float x, float y, float w, float h) {return new Rect(Screen.width*x, Screen.height*y, Screen.width*w, Screen.height*h);}
	
	
	float [] scaleImg (Texture2D img, float width, float height)
	{
		//
		//Regresa un vector con el tama;o de la imagen tal que esta mantenga su escala y no se vea pixeleada
		//Se va a escalar al ancho indicado si este es mayor que el alto (y viceversa)
		//
		float [] size = new float[2];
		
		if (width > height) {
			float scale =(float)((float)(img.height) / (float)(img.width));
			size [0] = width;
			size [1] = width * scale;
		} else {
			float scale =(float)((float)(img.width) / (float)(img.height));
			size [1] = height;
			size [0] = height * scale;
		}
		return size;
	}
}
