<div align="center">
    <a href="https://maikealame.github.io/laravel-auto/">
        <img src="https://github.com/maikealame/laravel-auto/raw/master/docs/images/logo-tp.png" height="128">
        <h1>Laravel Auto</h1>
    </a>
</div>

Laravel helper package to make automated lists with filters, sorting and paging like no other. 

Wiki: [https://maikealame.github.io/laravel-auto/](https://maikealame.github.io/laravel-auto/)

[![Latest Version](https://img.shields.io/github/release/maikealame/laravel-auto.svg?style=flat-square)](https://github.com/maikealame/laravel-auto/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/maikealame/laravel-auto.svg?style=flat-square)](https://packagist.org/packages/maikealame/laravel-auto)


You are free to create your own layout and style, there's no layout html/css included !
This package only grants a very automated query in Eloquent with Blade directives.

![table image](https://raw.githubusercontent.com/maikealame/laravel-auto/master/docs/images/examples/1.png)

---

![table image](https://raw.githubusercontent.com/maikealame/laravel-auto/master/docs/images/examples/2.png)

---

```
$categories = Topic::from("topics as t")
            ->select("t.*")
            ->leftJoin("portals as p", "p.id","=","t.portal_id")
            ->autoWhere()->autoSort()->autoPaginate();
```

![table image](https://raw.githubusercontent.com/maikealame/laravel-auto/master/docs/images/examples/3.png)

---

![table image](https://raw.githubusercontent.com/maikealame/laravel-auto/master/docs/images/examples/4.png)

---

```
$notifications = Notification::select("notifications.*", "notification_users.readed_at")
            ->groupBy("notifications.id")
            ->leftJoin("notifications_users", "notifications.id", "=", "notifications_users.notification_id")
            ->leftJoin("notifications_roles", "notifications.id", "=", "notifications_roles.notification_id")
            ->leftJoin("notifications_departments", "notifications.id", "=", "notifications_departments.notification_id")
            ->autoWhere(['or' => ["notifications.title", "notifications.description"]])
            ->autoSort(["notifications.updated_at", "desc"])->autoPaginate();
```

![table image](https://raw.githubusercontent.com/maikealame/laravel-auto/master/docs/images/examples/5.png)

---

```
if (Request::has("filter")) {
            if (isset(Request::get("filter")['keyword'])) {
                $keyword = Request::get("filter")['keyword'];
                Auto::setField("notifications.title", $keyword);
                Auto::setField("notifications.description", $keyword);

            }
}
$enterprises = Enterprises::from("enterprises as e"))
            ->select("e.*")
            ->leftJoin("enterprise_indicators_enterprises as iei","eie.enterprise_id","=","e.id")
            ->groupBy("e.id")
            ->autoWhere()->autoSort()->autoPaginate();
```

![table image](https://raw.githubusercontent.com/maikealame/laravel-auto/master/docs/images/examples/6.png)

---

See https://maikealame.github.io/laravel-auto/

---

- [Features](https://maikealame.github.io/laravel-auto#features)
- [What it do](https://maikealame.github.io/laravel-auto#what-it-do)
- [What is necessary](https://maikealame.github.io/laravel-auto#what-is-necessary)
- [Wiki](https://maikealame.github.io/laravel-auto#wiki)
- [Examples](https://maikealame.github.io/laravel-auto#example)
- [How works](https://maikealame.github.io/laravel-auto#how-works)
1. [View](https://maikealame.github.io/laravel-auto#view)
2. [Controller](https://maikealame.github.io/laravel-auto#controller)
3. [Model](https://maikealame.github.io/laravel-auto#model)
- [How to install](https://maikealame.github.io/laravel-auto#how-to-install)
