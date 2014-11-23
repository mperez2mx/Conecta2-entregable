using UnityEngine;
using System.Collections;
 
public enum views {NOGPS, MAPAO, MAPAL, BRUJULA, REALIDAD, EVAL};

public class Gps : MonoBehaviour 
{	
	public string lonselec, latselec;
	public int votos;

	public Texture2D tex;
	public float lon, lat, zoom = 13, ranking;
	public views v;
	public bool primer = true;
	WWW www;
	bool ocupado;
	int maxWait, errno;

  	IEnumerator StartwGPS ()
	{
		//
		if (!Input.location.isEnabledByUser) 
		{
			v = views.NOGPS; errno = 1;
		}
		else
		{
			Input.location.Start(5f, 5f); //con el stat activas el sensor. los parametros son para la presición, mientras menor sean los número, más preciso y más energía gastas. Se peude dejar en blanco y quedan 10 y 10.
			InvokeRepeating("updateGPS", 0, 1); // InvokeRpeating manda a llamar una función en determinado tiempo y cada determinado tiempo

			for(maxWait = 30; Input.location.status == LocationServiceStatus.Initializing && maxWait > 0; maxWait--) 
				yield return new WaitForSeconds(1);
			if (maxWait < 1) {v = views.NOGPS; errno = 3;}
			if (Input.location.status == LocationServiceStatus.Failed){ v = views.NOGPS; errno = 2;}
			else v = views.MAPAL;
		}
	}
	
	void Start()
	{
		GetComponent<DataAccessPoints>().fetchDataFromURL();
		if(Application.platform == RuntimePlatform.Android)
		{
			StartCoroutine( StartwGPS());
		}
		else
		{
			lon = -99.24f; lat = 18.92f;
		}
		v = views.MAPAL;
  	}

	void OnGUI ()
	{
		if(v == views.NOGPS)
			GetComponent<Nogps>().show(errno);
		if(v == views.MAPAL)
			GetComponent<Mapal>().show();
		if(v == views.MAPAO)
			GetComponent<Mapao>().show();
		if(v == views.BRUJULA)
			GetComponent<Brujula>().show();
		if(v == views.REALIDAD)
			GetComponent<Realidad>().show();
		if(v == views.EVAL)
			GetComponent<Evaluacion>().show();

		GetComponent<Menu>().show();
	}

	void updateGPS() { lat = Input.location.lastData.latitude; lon = Input.location.lastData.longitude;}
	
	public IEnumerator GetData(float dzoom)
	{
		Debug.Log("getData");
		if(!ocupado) //es como un semáforo, sin esto, cuando pides dos imágenes muy rápido truena
		{
			ocupado = true;
			www = new WWW("http://maps.googleapis.com/maps/api/staticmap?center="+ lonselec + "," + latselec + "&zoom="+ (zoom += dzoom) +"&size=1920x1920&markers=color:red%7CLabel:B%7C"+ lonselec + "," + latselec + "&markers=color:blue%7CLabel:A%C"+ lon + "," + lat + "&sensor=true");
			yield return www; // yield espera a que termine todo lo de arriba y con return a puedes seguir.
			tex = www.texture;
			ocupado = false;
		}
	}
	Rect onScreen(float x, float y, float w, float h) {return new Rect(Screen.width*x, Screen.height*y, Screen.width*w, Screen.height*h);}
}