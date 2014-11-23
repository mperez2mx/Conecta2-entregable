using System.Collections;

public class LatLongStruct 
{
	private string longitude;
	private string latitude;
	public float pit;
	public float rango;
	public string nombre, cat;
	public int votos;
	
	public LatLongStruct(string latitude, string longitude, string nombre, string cat, string rango, int votos){
		this.longitude = longitude;
		this.latitude = latitude;
		this.rango = float.Parse (rango);
		this.nombre = nombre;
		this.cat = cat;
		this.votos = votos;
	}
	
	public string getLatitude(){
		return latitude;
	}
	
	public string getLongitude(){
		return longitude;
	}
}


