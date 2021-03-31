function newRegexp(pattern) {
    var with_flags = pattern.match(/^\/(.+)\/(.+)$/);
    var without_flags = pattern.match(/^\/(.+)\/$/);
    if (without_flags) {
        return new RegExp(without_flags[1]);
    } else {
        return new RegExp(with_flags[1], with_flags[2]);
    }
}