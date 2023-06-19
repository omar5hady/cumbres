const names = ["MARIA","MA.","MA","JOSE","J","J.","DA","DAS", "DE", "DEL", "DER",
                "DI", "DIE", "DD", "EL", "LA", "LOS", "LAS", "LE", "LES", "MAC",
                "MC", "VAN", "VON", "Y"];

const special = ['!','"','#','$','%','&','(',')','*','+',',','-','.','/','^',
                    '[',']','{','}','~',"'"];

const prepo = ["DA", "DAS", "DE", "DEL", "DER", "DI", "DIE", "DD", "EL", "LA",
                "LOS", "LAS", "LE", "LES", "MAC", "MC", "VAN", "VON", "Y"];

const ants = ["BACA","BAKA","BUEI","BUEY","CACA","CACO","CAGA","CAGO","CAKA",
                "CAKO","COGE","COGI","COJA","COJE","COJI","COJO","COLA","CULO",
                "FALO","FETO","GETA","GUEI","GUEY","JETA","JOTO","KACA","KACO",
                "KAGA","KAGO","KAKA","KAKO","KOGE","KOGI","KOJA","KOJE","KOJI",
                "KOJO","KOLA","KULO","LILO","LOCA","LOCO","LOKA","LOKO","MAME",
                "MAMO","MEAR","MEAS","MEON","MIAR","MION","MOCO","MOKO","MULA",
                "MULO","NACA","NACO","PEDA","PEDO","PENE","PIPI","PITO","POPO",
                "PUTA","PUTO","QULO","RATA","ROBA","ROBE","ROBO","RUIN","SENO",
                "TETA","VACA","VAGA","VAGO","VAKA","VUEI","VUEY","WUEI","WUEY"];

const estado = ["Aguascalientes","Baja California","Baja California Sur",
                    "Campeche","Coahuila de Zaragoza","Colima","Chiapas","Chihuahua",
                    "Distrito Federal","Durango","Guanajuato","Guerrero","Hidalgo",
                    "Jalisco","México","Michoacán de Ocampo","Morelos","Nayarit","Nuevo León",
                    "Oaxaca","Puebla","Querétaro","Quintana Roo","San Luis Potosí",
                    "Sinaloa","Sonora","Tabasco","Tamaulipas","Tlaxcala","Veracruz de Ignacio de la Llave",
                    "Yucatán","Zacatecas","EXTRANJERO"];

const ef = ["AS","BC","BS","CC","CL","CM","CS","CH","DF","DG","GT","GR","HG",
            "JC","MC","MN","MS","NT","NL","OC","PL","QT","QR","SP","SL","SR",
            "TC","TS","TL","VZ","YN","ZS","NE"];

const abc = ["B","C","D","F","G","H","J","K","L","M","N","Ñ","P","Q","R","S",
"T","V","W","X","Y","Z","\u00d1"];

const cname = ["MARIA","JOSE"];


const createCurpRFC = (apellidos, apellidos2, nombre, fecha_nac, sexo, lugar_nacimiento ) => {

    let i;

    //{{{{{{{{{{{{{{{{{{paterno}}}}}}}}}}}}}}}}}}
    let paterno = apellidos.toUpperCase();
    let paterno2 = paterno.split(" ");
    let paternoL ="";

    if(paterno2.length > 1){
        let paterno1 ="";
        if(prepo.indexOf(paterno2[0]) >= 0){
            for(i = 1; i < paterno2.length; i++){
                paterno1 = paterno1+paterno2[i]+" ";
            }
        }else{
            for(i = 0; i < paterno2.length; i++){
                paterno1 = paterno1+paterno2[i]+" ";
            }
        }
        paterno = paterno1.split("");
    }else{paterno = paterno2[0].split("");}

    for (i = 1; i < 100; i++) {
        if (paterno[i] == "A") {
            paternoL = paterno[i];
            i = 101;
        }
        if (paterno[i] == "E") {
            paternoL = paterno[i];
            i = 101;
        }
        if (paterno[i] == "I") {
            paternoL = paterno[i];
            i = 101;
        }
        if (paterno[i] == "O") {
            paternoL = paterno[i];
            i = 101;
        }
        if (paterno[i] == "U") {
            paternoL = paterno[i];
            i = 101;
        }
    }

    if(paternoL == ""){ paternoL = "X" }
    if(paterno[0] == "\u00d1"){paterno[0]="X"; }//para la Ñ
    if(special.indexOf(paterno[0]) > 0){ paterno[0] = "X";}
    if(special.indexOf(paternoL) > 0){ paternoL = "X";}

    //{{{{{{{{{{{{{{{{{{materno}}}}}}}}}}}}}}}}}}
    let materno;
    materno = apellidos2.toUpperCase();
    let materno2 = materno.split(" ");

    if(materno2.length > 1){
        let materno1 = "";
        if(prepo.indexOf(materno2[0]) >= 0){
            for(i = 1; i < materno2.length; i++){
                materno1 = materno1+materno2[i]+" ";
            }
        }else{
            for(i = 0; i < materno2.length; i++){
                materno1 = materno1+materno2[i]+" ";
            }
        }
        materno = materno1.split("");
    }else{materno = materno2[0].split("");}

    if(materno == ""){materno[0]="X"; }
    if(materno[0] == "\u00d1"){materno[0]="X"; }//para la Ñ
    if(special.indexOf(materno[0]) > 0){ materno[0] = "X";}

    //{{{{{{{{{{{{{{{{{{nombre}}}}}}}}}}}}}}}}}}
    let name;
    name = nombre.toUpperCase();
    let name2 = name.split(' ');

    if(name2.length > 1){
        let name1 = "";
        if(names.indexOf(name2[0]) >= 0){
            for(i = 1; i < name2.length; i++){
                name1 = name1+name2[i]+" ";
            }
        }else{
            for(i = 0; i < name2.length; i++){
                name1 = name1+name2[i]+" ";
            }
        }
        name = name1.split("");
    }else{name = name2[0].split("");}

    if(name[0] == "\u00d1"){name[0]="X"; }//para la Ñ
    if(special.indexOf(name[0]) > 0){ name[0] = "X";}

    //{{{{{{{{{{{{{{{{{{part 1}}}}}}}}}}}}}}}}}}

    let p1 = paterno[0] + paternoL + materno[0] + name[0];

    if(ants.indexOf(p1) >= 0){
        let x = p1.split("");
        x[1] = "X";
        p1="";
        for(i=0; i < 4; i++){
            p1=p1+x[i];
        }
    }

    //{{{{{{{{{{{{{{{{{{date}}}}}}}}}}}}}}}}}}
    let date = fecha_nac.split('');
    let p2 = date[2]+date[3]+date[5]+date[6]+date[8]+date[9];

    //{{{{{{{{{{{{{{{{{{part 3}}}}}}}}}}}}}}}}}}
    let sex = sexo;
    let p3 = "";
    if(sex == "F"){p3 = "M";}
    if(sex == "M"){p3 = "H";}

    //{{{{{{{{{{{{{{{{{{estado}}}}}}}}}}}}}}}}}}

    let stat = lugar_nacimiento;
    let p4 ="";
    p4= ef[estado.indexOf(stat)];

    //{{{{{{{{{{{{{{{{{{conson}}}}}}}}}}}}}}}}}}
    let c14 = "";
    let vtemp = "";
    let i2 ="";

    c14 = apellidos.toUpperCase().split(" ");

    if(c14.length > 1){
        for(i=0;i < c14.length;i++){
            vtemp = c14[i].split("");
            for(i2 = 1; i2 < vtemp.length;i2++){
                if(abc.indexOf(vtemp[i2])>=0){
                    c14 = vtemp[i2];
                    i = c14.length+1;
                    i2 = vtemp.length+1
                }
            }
        }
    }else{
        vtemp = c14[0].split("");
        for(i2 = 1; i2 < vtemp.length;i2++){
            if(abc.indexOf(vtemp[i2])>=0){
                c14 = vtemp[i2];
                i = c14.length+1;
                i2 = vtemp.length+1
            }
        }
    }

    if(abc.indexOf(c14)>=0){}else{
        c14 = "X";
    }
    if(c14 == "\u00d1"){c14="X"; }//para la Ñ
    //{{{{{{p15}}}}}}
    let c15 = "";

    c15 = apellidos2.toUpperCase().split(" ");

    if(c15.length > 1){
        for(i=0;i < c15.length;i++){
            vtemp = c15[i].split("");
            for(i2 = 1; i2 < vtemp.length;i2++){
                if(abc.indexOf(vtemp[i2])>=0){
                    c15 = vtemp[i2];
                    i = c15.length+1;
                    i2 = vtemp.length+1
                }
            }
        }
    }else{
        vtemp = c15[0].split("");
        for(i2 = 1; i2 < vtemp.length;i2++){
            if(abc.indexOf(vtemp[i2])>=0){
                c15 = vtemp[i2];
                i = c15.length+1;
                i2 = vtemp.length+1
            }
        }
    }

    if(abc.indexOf(c15)>=0){}else{
        c15 = "X";
    }
    if(c15 == "\u00d1"){c15="X"; }//para la Ñ
    //{{{{{{p16}}}}}}
    let c16 = "";

    c16 = nombre.toUpperCase().split(" ");

    if(c16.length > 1){
        if(cname.indexOf(c16[0]) >= 0){
            for(i=1;i < c16.length;i++){
                vtemp = c16[i].split("");
                for(i2 = 1; i2 < vtemp.length;i2++){
                    if(abc.indexOf(vtemp[i2])>=0){
                        c16 = vtemp[i2];
                        i = c16.length+1;
                        i2 = vtemp.length+1
                    }
                }
            }
        }else{
            for(i=0;i < c16.length;i++){
                vtemp = c16[i].split("");
                for(i2 = 1; i2 < vtemp.length;i2++){
                    if(abc.indexOf(vtemp[i2])>=0){
                        c16 = vtemp[i2];
                        i = c16.length+1;
                        i2 = vtemp.length+1
                    }
                }
            }
        }
    }else{
        vtemp = c16[0].split("");
        for(i2 = 1; i2 < vtemp.length;i2++){
            if(abc.indexOf(vtemp[i2])>=0){
                c16 = vtemp[i2];
                i = c16.length+1;
                i2 = vtemp.length+1
            }
        }
    }

    if(abc.indexOf(c16)>=0){}else{
        c16 = "X";
    }
    if(c16 == "\u00d1"){c16="X"; }//para la Ñ

    const curp = p1 + p2 + p3 + p4 + c14 + c15 + c16;
    const rfc = p1 + p2;

    return {
        curp :curp,
        rfc :rfc
    }
}

export default createCurpRFC
