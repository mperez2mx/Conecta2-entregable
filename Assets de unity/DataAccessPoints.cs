using UnityEngine;
using System.Collections;

public class DataAccessPoints : MonoBehaviour {
	string data = "";
	WWW www;
	LatLongStruct[] arreglo;

	public void fetchDataFromURL()
	{
		StartCoroutine(fetch());
	}

	private IEnumerator fetch()
	{
		www = new WWW ("http://miguelp.com/Conecta2/test.php");
		yield return www;

		if(string.IsNullOrEmpty(www.error))
		{
			data = www.text;
			PlayerPrefs.SetString("accespoints", data);
			Debug.Log("crgue data, l: " +data.Length);
		}
		else
		{
			Debug.Log("error www");
			if(PlayerPrefs.GetString("accespoints") == "")
			{
				GetComponent<Gps>().v = views.NOGPS;
				Debug.Log("estuvo vacia");
			}
			else
			{
				data = PlayerPrefs.GetString("accespoints");
				Debug.Log("no estuvo vacio y es esto: |"+ data + "|");
			}
		}
	}

	public LatLongStruct[] getLatitudes()
	{
		string[] arr = data.Split ('&');

		if(arr.Length < 5) return new LatLongStruct[0];
		Debug.Log(arr.Length);

		int ku = 0;
		arreglo = new LatLongStruct[arr.Length-1];
		for (int k = 0; k<arr.Length-1; k++)
		{

			string[] latLong = arr[k].Split('|');
			if(Mathf.Abs(GetComponent<Gps>().lon - float.Parse(latLong[0])) < 0.002f)
				arreglo[ku++] = new LatLongStruct(latLong[1],latLong[0], latLong[2], latLong[3], latLong[4], int.Parse(latLong[5]));
		}
		Debug.Log("ku:" + ku);

		for(int k = 0; k< ku; k++)
			arreglo[k].pit = pit(k);

		int [] indexes = new int[9];
		for(int j=0; j < 9; j++)
		{
			//Debug.Log(j+"/5");
			float min = int.MaxValue;
			int index = -1;
			for(int k = 0; k < ku; k++)
			{
				if(arreglo[k].pit < min) 
				{
					min = arreglo[k].pit;
					index = k;
				}
			}
			arreglo[index].pit = int.MaxValue;
			indexes[j] = index;
		}

		LatLongStruct [] result = new LatLongStruct[9];
		for(int l=0; l < 9; l++)
			result[l] = arreglo[l];

		return result;
	}

	public float pit(int k)
	{
		return Mathf.Pow(Mathf.Pow(GetComponent<Gps>().lon - float.Parse(arreglo[k].getLongitude()), 2f) + Mathf.Pow(GetComponent<Gps>().lat - float.Parse(arreglo[k].getLatitude()),2),0.5f);
	}

	public string getData()
	{
		return data;
	}

}
