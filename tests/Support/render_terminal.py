#!/usr/bin/env python3

import json
import sys

try:
    import pyte
except ImportError as exc:
    print(f"pyte is required: {exc}", file=sys.stderr)
    sys.exit(2)


def main() -> int:
    if len(sys.argv) != 3:
        print("usage: render_terminal.py <rows> <cols>", file=sys.stderr)
        return 1

    rows = int(sys.argv[1])
    cols = int(sys.argv[2])

    data = sys.stdin.buffer.read().decode("latin1")

    screen = pyte.Screen(cols, rows)
    stream = pyte.Stream(screen)
    stream.feed(data)

    result = {
        "lines": list(screen.display),
        "cursor_row": screen.cursor.y + 1,
        "cursor_col": screen.cursor.x + 1,
    }
    json.dump(result, sys.stdout)
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
