using UnityEngine;
using System.Collections;


public class Realidad : MonoBehaviour 
{
	bool primer = true;
	public GUIStyle s;
	WebCamTexture wct;
	Texture2D img;
	double a;
	double min;
	double [] dif = new double [8];
	int index = 0;
	public void show()
	{
		if(primer)
		{
			Input.compass.enabled = true;
			Input.gyro.enabled = true;
			s.normal.textColor = Color.black;
			s.fontSize = 60;
			foreach (WebCamDevice wcd in WebCamTexture.devices) 
				if (!wcd.isFrontFacing) (wct = new WebCamTexture(wcd.name, 200, 200/9*16)).Play();
			img = new Texture2D(wct.width, wct.height);
			primer = false;
		}

		img.SetPixels(wct.GetPixels());
		img.Apply();

		GUIUtility.RotateAroundPivot(90, new Vector2(.5f*Screen.width, .5f*Screen.height));
		GUI.DrawTexture(new Rect(-420, 312f, 1728f, 1296f), img);
		GUIUtility.RotateAroundPivot(-90, new Vector2(.5f*Screen.width, .5f*Screen.height));

		a = -Input.compass.magneticHeading-180/Mathf.PI*Mathf.Atan((    (float.Parse(GetComponent<Gps>().lonselec)-GetComponent<Gps>().lat)    )/(  (float.Parse(GetComponent<Gps>().latselec)-GetComponent<Gps>().lon)    ));

		min = int.MaxValue;

		string st="";
		if(Mathf.Abs((float)a-0f) < min) {min = Mathf.Abs((float)a-0f); dif[index] = a-0; st= "uno";}
		if(Mathf.Abs((float)a-360f) < min) {min = Mathf.Abs((float)a-360f); dif[index] = a-360; st = "dos";}
		if(Mathf.Abs((float)a+360f) < min) {min = Mathf.Abs((float)a+360f); dif[index] = a+360;st = "tres";}


		//GUI.Label(onScreen(.1f, .7f, 1f, .1f), Input.gyro.gravity.x+ "\n"+ Input.gyro.gravity.y+ "\n"+Input.gyro.gravity.z+ "\n"+ a +  "\n"+ dif + ", "+st, s);
		

		GUI.DrawTexture(onScreen(.5f+(float)(dif[0]+dif[1]+dif[2]+dif[3]+dif[4]+dif[6]+dif[7])/75f, 0.5f+Input.gyro.gravity.z*0.5f,0.07f, 0.07f), (Texture2D)Resources.Load("arrow"));
		index++; if(index==8) index = 0;
	}

	Rect onScreen(float x, float y, float w, float h) {return new Rect(Screen.width*x, Screen.height*y, Screen.width*w, Screen.height*h);}
}
