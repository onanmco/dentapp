function newRegexp(regex) {
    var with_flags = regex.match(/^\/(.+)\/(.+)$/);
    var without_flags = regex.match(/^\/(.+)\/$/);
    if (without_flags) {
        return new RegExp(without_flags[1]);
    } else {
        return new RegExp(with_flags[1], with_flags[2]);
    }
}