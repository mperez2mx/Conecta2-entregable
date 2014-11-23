using UnityEngine;
using System.Collections;
using System;

public class Brujula : MonoBehaviour 
{
	float[] a = new float[8];
	bool primer = true;
	public GUIStyle s;
	int index = 0;

	public void show()
	{
		if(primer)
		{
			s.normal.textColor = new Color(.375f, .375f, .375f);
			s.fontSize = 60;
			s.alignment = TextAnchor.MiddleCenter;
			Input.compass.enabled = true;
			primer = false;
		}

		GUI.DrawTexture(onScreen(.1f,.2f,.8f,(9f/16f)*.8f), (Texture2D)Resources.Load("brujulaFondo"));

		a[index] = 180/Mathf.PI*Mathf.Atan(   (float.Parse(GetComponent<Gps>().lonselec)-GetComponent<Gps>().lat)    /(  (float.Parse(GetComponent<Gps>().latselec)-GetComponent<Gps>().lon)    ));

		GUIUtility.RotateAroundPivot(-Input.compass.magneticHeading-(a[0]+a[1]+a[2]+a[3]+a[4]+a[5]+a[6]+a[7])/8, new Vector2(.5f*Screen.width, ((9f/16f)*.4f+.2f)*Screen.height));
			GUI.DrawTexture(onScreen(.45f,.2f,.1f,(9f/16f)*.8f), (Texture2D)Resources.Load("brujulaAguja"));
		GUIUtility.RotateAroundPivot(Input.compass.magneticHeading+(a[0]+a[1]+a[2]+a[3]+a[4]+a[5]+a[6]+a[7])/8, new Vector2(.5f*Screen.width, ((9f/16f)*.4f+.2f)*Screen.height));

		GUI.Label(onScreen(.2f, .69f, .6f, .2f), "Internet a "+ distance(float.Parse(GetComponent<Gps>().lonselec), float.Parse(GetComponent<Gps>().latselec), GetComponent<Gps>().lat, GetComponent<Gps>().lon, 'K', s).ToString("N") +" metros de tí", s);
		index++; if (index ==8) index = 0;
	}

	public static double distance(double lat1, double lon1, double lat2, double lon2, char unit, GUIStyle s) 
	{
		//GUI.Label(onScreen(.1f, .65f, 1f, 1f), "Recibí "+ lat1 + ", "+ lon1 + ", "+ lat2 + ", "+ lon2 + ", ", s);
		
		double theta = lon1 - lon2;
		double dist = Math.Sin(deg2rad(lat1)) * Math.Sin(deg2rad(lat2)) + Math.Cos(deg2rad(lat1)) * Math.Cos(deg2rad(lat2)) * Math.Cos(deg2rad(theta));
		dist = Math.Acos(dist);
		dist = dist*180/Math.PI;
		dist = dist * 60 * 1.1515;
		if (unit == 'K') {
			dist = dist * 1609.344;
		} else if (unit == 'N') {
			dist = dist * 0.8684;
		}
		return (dist);
	}
	
	
	static public double deg2rad(double f)
	{
		return f/180*Math.PI;
	}
	
	static Rect onScreen(float x, float y, float w, float h) {return new Rect(Screen.width*x, Screen.height*y, Screen.width*w, Screen.height*h);}

}
