
set -um

[[ $# -gt 0 ]] || { sed -n '2,/^#$/ s/^# //p' <"$0"; exit 1; }

pgid=$(ps -o pgid= $$)

case $(uname) in
    Darwin|s*BSD) sizes() { /bin/ps -o rss= -g $1; } ;;
    Linux) sizes() { /bin/ps -o rss= -$1; } ;;
    *) echo "$(uname): unsupported operating system" >&2; exit 2 ;;
esac

(
peak=0
while sizes=$(sizes $pgid)
do
    set -- $sizes
    sample=$((${@/#/+}))
    let peak="sample > peak ? sample : peak"
    sleep 0.1
done
echo "$peak"
) &
monpid=$!

exec (docker run -i --rm -v "$PWD":/judge -w /judge gcc:4.9 timeout 1 ./main<in.txt >out.txt 2>error.txt)

