import type { Project } from "./types";

/**
 * A stable temporary visual for projects without a CMS-uploaded screenshot.
 * The seed makes the result consistent per project, while a real `image_url`
 * from the backend always takes priority and replaces it automatically.
 */
export function temporaryProjectImage(project: Project): string {
  return `https://picsum.photos/seed/${encodeURIComponent(project.title)}/1280/720`;
}
