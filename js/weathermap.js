function weathermaptickposition(d, type, invert) {
    var dist = 15;
    if (d.position > 0) {
        dist = dist * d.position;
        if (parseInt(d.position)) {
            angle = Math.atan2(d.ty - d.sy, d.tx - d.sx);
        } else {
            angle = Math.atan2(d.ty - d.sy, d.tx - d.sx);
        }
        if (type == "x") {
            if (invert) {
                return -Math.sin(angle) * dist + d.sx;
            } else {
                return Math.sin(angle) * dist + d.sx;
            }
        } else {
            if (invert) {
                return Math.cos(angle) * dist + d.sy;
            } else {
                return -Math.cos(angle) * dist + d.sy;
            }
        }
    } else {
        if (type == "x") {
            return d.sx;
        } else {
            return d.sy;
        }
    }
}
