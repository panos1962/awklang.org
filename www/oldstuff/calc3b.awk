# calc3 - infix calculator - derived from calc3 in TAPL, chapter 6.
# by Kenny McCormack, Mon 3 Jan 2000
# modified by Alan Linton, $Date: 2000/01/06 21:37:36 $, $Revision: 1.16 $

BEGIN    { eval("x=86") ; eval("y=99") }

{
  printf "%20s = %15s\n", $0,eval($0)
}

# The rest is functions...
function eval(s  ,e) {
    _S_expr = s
    gsub(/[ \t]+/,"",_S_expr)
    if (length(_S_expr)==0) return 0
    _f = 1
    e = _expr()
    if (_f <= length(_S_expr))
        printf("An error occurred at %s\n", substr(_S_expr,_f))
    else return e
    }

function _expr(  var,e) {        # term | term [+-] term
    if (match(substr(_S_expr,_f),/^[A-Za-z_][A-Za-z0-9_]*=/)) {
        var = _advance()
        sub(/=$/,"",var)
        return _vars[var] = _expr()
        }
    e = _term()
    while (substr(_S_expr,_f,1) ~ /[+-]/)
        e = substr(_S_expr,_f++,1) == "+" ? e + _term() : e - _term()
    return e
    }

function _term(  e) {        # factor | factor [*/%] factor
    e = _factor()
    while (substr(_S_expr,_f,1) ~ /[*\/%]/) {
        _f++
        if (substr(_S_expr,_f-1,1) == "*") return e * _factor()
        if (substr(_S_expr,_f-1,1) == "/") return e / _factor()
        if (substr(_S_expr,_f-1,1) == "%") return e % _factor()
        }
        return e
    }

function _factor(  e) {      # factor2 | factor2^factor
    e = _factor2()
    if (substr(_S_expr,_f,1) != "^") return e
    _f++
    return e^_factor()
    }

function _factor2(  e) {     # [+-]?factor3 | !*factor2
    e = substr(_S_expr,_f)
    if (e~/^[\+\-\!]/) {  #unary operators [+-!]
        _f++
        if (e~/^\+/) return +_factor3()     # only one unary + allowed
        if (e~/^\-/) return -_factor3()     # only one unary - allowed
        if (e~/^\!/) return !(_factor2()+0) # unary ! may repeat
        }
    return _factor3()
    }

function _factor3(  e,fun,e2) { # number | varname | (expr) | function(...)
    e = substr(_S_expr,_f)

    #number
    if (match(e,/^([0-9]+[.]?[0-9]*|[.][0-9]+)([Ee][+-]?[0-9]+)?/)) {
        return _advance()
        }

    #function()
    if (match(e,/^([A-Za-z_][A-Za-z0-9_]+)?\(\)/)) {
        fun=_advance()
        # escaped parens in next two lines required by mawk1.2.2 (PST)
        if (fun~/^srand\(\)/) return srand()
        if (fun~/^rand\(\)/)  return rand()
        printf("error: unknown function %s\n", fun)
        return 0
        }

    #(expr) | function(expr) | function(expr,expr)
    if (match(e,/^([A-Za-z_][A-Za-z0-9_]+)?\(/)) {
        fun=_advance()
        if (fun~/^((cos)|(exp)|(int)|(log)|(sin)|(sqrt)|(srand))?\(/) {
            e=_expr()
            e=_calcfun(fun,e)
            }
            else if (fun~/^atan2\(/) {
                e=_expr()
            if (substr(_S_expr,_f,1) != ",") {
                printf("error: missing , at %s\n", substr(_S_expr,_f))
                return 0
                }
            _f++
            e2=_expr()
            e=atan2(e,e2)
            }
        else {
            printf("error: unknown function %s\n", fun)
            return 0
            }
        if (substr(_S_expr,_f++,1) != ")") {
            printf("error: missing ) at %s\n", substr(_S_expr,_f))
            return 0
            }
            return e
        }

    #variable name
    if (match(e,/^[A-Za-z_][A-Za-z0-9_]*/)) {
        return _vars[_advance()]
        }

    #error
    printf("error in factor: expected number or ( at %s\n", substr(_S_expr,_f))
    return 0
    }

function _calcfun(fun,e) {    #built-in functions of one variable
    if (fun=="(") return e
    if (fun=="cos(") return cos(e)
    if (fun=="exp(") return exp(e)
    if (fun=="int(") return int(e)
    if (fun=="log(") return log(e)
    if (fun=="sin(") return sin(e)
    if (fun=="sqrt(") return sqrt(e)
    if (fun=="srand(") return srand(e)
    }

function _advance(   tmp) {
    tmp = substr(_S_expr,_f,RLENGTH)
    _f += RLENGTH
    return tmp
    }

# To test the program I used the following interpreters
# gawk 3.0.3 djgpp
# gawk 3.0.4 djgpp
# gawk 3.0.4 win32
# awk95
#
# with the following test data
#
# x=7
# 1+1
# 1  +    1       
# 1-1
# 1/3
# -1/-1
# x=7
# -.35e+4/-1E-5+0.03e6
# x
# -x
# x/y
# +x/y
# x/+y
# x/-y
# x=7
# -x/y
# -x/-y
# !x
# !(!x)
# (1+2*x)/-(-1-y/3)
# 5%3
# 1+2*3
# 1+2*3^4
# (1*2+3*4)^-(5*6+7*8)
# 4^3^2
# sin(1)
# cos(1)
# srand()
# srand(1)
# atan2(1,1)
# rand()
#
# The results were correct and the different awk interpreters gave consistent
# results except for srand() and rand().
# -- 
# Alan Linton
