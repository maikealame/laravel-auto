# LaravelAuto
**[ in developing yet ]** a Laravel helper package to make all or almost everything for your projects

[![Latest Version](https://img.shields.io/github/release/maikealame/laravel-auto.svg?style=flat-square)](https://github.com/maikealame/laravel-auto/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/maikealame/laravel-auto.svg?style=flat-square)](https://packagist.org/packages/maikealame/laravel-auto)
[![Build Status](https://travis-ci.org/maikealame/laravel-auto.svg?branch=master)](https://travis-ci.org/maikealame/laravel-auto)

- Create automatically sql where conditions (mysql and postgresql support)
- Create list filters functionality with flexible code for your own layout
- Create sortable functionality with flexible code for your own layout
- Create pagination to combine with this package methods
- Create list length combobox

## What is necessary ?

To work all modules, it's necessary the following dependencies:

- Laravel 5.*
- Font-awesome
- Bootstrap
- Jquery

## Wiki ?

Soon...


## Example ?

Just for now

`[url] http://localhost/ocorrencias?sort=id&order=desc&filter[t.id]=>1`

![table image](https://raw.githubusercontent.com/maikealame/laravel-auto/master/docs/ex.PNG)

## How works ?

### [view]

Create your table with bootstrap or not, use all your logic with blades like normally, and use the blade directives of the package:

- *Generate a script code to filter table without form, pass in param the selector of button will trigger your filter*

`@autowherescript('.btn-filter')`

---

- *Generate a script code to async the reload table when paging, pass in param many selectors elements will you want replace*

`@autopagesasync('.panel-table')`

`@autopagesasync('.panel-table tbody', '.panel-table .panel-footer')`

---

**Usage:**

```
<head>
@autowherescript('.btn-filter')
@autopagesasync('.panel-table')
</head>
```

---

- *Call column header title, when click sort this column will happen.*

`@autosort('name','Nome')`

**param[0]: column table** - required, can bring sql alias

**param[1]: text inside `<a>`** - not required

---

- *Bring the value previously used in filter to input tag value, to not loss when reload page*

`@autowherefilter('name')`

`@autowherefilter('id', 1)`

`@autowherefilter('id', 1, "checked")`

*Can pass 2nd param, thats indicates the tag is a select option. What to do? pass the option value in 2nd param and the return is "selected" if you filter with that option selected*

*You can overwrite "selected" return to other string, like "checked" when pass as 3rd param*

---

- *Get a select tag with length of pagination, when change reload table with new length*

`@autopageslength($tickets)`

**param[0]: Paginator** - use an Eloquent or QueryBuilder and finish the query with paginate() to get Paginator

- *Get the pagination buttons*

`@autopages($tickets)`

**param[0]: Paginator** - use an Eloquent or QueryBuilder and finish the query with paginate() to get Paginator

---

*Usage:*

```
<div class="panel panel-table">
    <div class="panel-heading">
        <h3 class="panel-title">
            Registros
        </h3>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr role="row">
                <th>@autosort('t.id','Protocolo')</th>
                <th>@autosort('t.start','Data Abertura')</th>
                <th>@autosort('t.end','Data Fechamento')</th>
                <th>@autosort('p.name','Portfolio')</th>
                <th class="tc"><b class="fa fa-gear"></b></th>
            </tr>
            <tr>
                <th><input type="text" class="form-control" name="t.id" value="@autowherefilter('t.id')"></th>
                <th><input type="text" class="form-control datepicker" name="t.start" value="@autowherefilter('t.start')"></th>
                <th><input type="text" class="form-control datepicker" name="t.end" value="@autowherefilter('t.end')"></th>
                <th>
                    <select name="p.id">
                        <option value="1" @autowherefilter('p.id',1)>Portfolio 1</option
                        <option value="2" @autowherefilter('p.id',2)>Portfolio 2</option
                        <option value="3" @autowherefilter('p.id',3)>Portfolio 3</option
                    </select>
                </th>
                <th class="tc"><button class="btn btn-primary btn-filter"><i class="fa fa-search"></i></button></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{$ticket->protocol}}</td>
                    <td>{{$ticket->start}}</td>
                    <td>{{$ticket->end}}</td>
                    <td>{{$ticket->portfolio}}</td>
                    <td class="tc">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="panel-footer clearfix">
        <div class="col pull-left">
            Mostrando @autopageslength($tickets) de {{$tickets->total()}}
        </div>
        <div class="col pull-right">
            @autopages($tickets)
        </div>
    </div>
</div>
```


### [Controller]

Let's see it:
```
use App\Http\Controllers\Controller;

class TestsController extends Controller
{
    public function autowhere(){
        $tickets = \App\Ticket::autoWhere()->autoSort()->autoPaginate();
        return view("tests.ticket",compact("tickets","length"));
    }

    public function autowherealias()
    {
        $tickets = \App\Ticket::from('ticket as t')
            ->select('t.id as id',
                't.start as start',
                't.end as end',
                'p.name as portfolio'
            )
            ->leftJoin('portfolio as p', 't.portfolio_id', '=', 'p.id')
            ->autoWhere()
            ->autoSort()
            ->autoPaginate();
        return view("tests.ticket_alias", compact("tickets"));
    }
}
```

Ok, where autoWhere, autoSort and autoPaginate come from ? See [model] section to understand.
It's very simple and light for yout code, even I don't believe.

### [Model]

What happens here is simple too:

```
use Auto\AutoWhere;
use Auto\AutoSort;
use Auto\AutoPaginate;

class Ticket extends Model
{
    use AutoWhere, AutoSort, AutoPaginate;
    ...
}
```

Just using traits, We manipulate the QueryBuilder until we get the expected result. Of course, with other magics.


## How to install ?

Now the times arrived. check out:

.1 Download package with Composer

```
$ composer require maikealame/laravel-auto
```

Check the [`composer.json`]

```
"require": {
  ...
  "maikealame/laravel-auto": "*"
}
```

.2 Add this package to your application service providers in `config/app.php`

```
'providers' => [
    ...
    App\Providers\RouteServiceProvider::class,

    /*
     * Third Party Service Providers...
     */
    Auto\AutoServiceProvider::class,
],
```

.3 Publish the package configuration file to your application.

```
$ php artisan vendor:publish --provider="Auto\AutoServiceProvider" --tag="config"
```

Now we done, take a coffe and start code.

**Any feedback it's welcome, issues are here for it.**
